<?php

namespace App\Http\Controllers;

use App\Http\Resources\UtilizadorResource;
use Illuminate\Http\Request;
use App\Models\Utilizador;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Registered;

class UtilizadorController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login",
     *     description="Realiza o login de um utilizador.",
     *     tags={"Utilizadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados de login",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="example@example.com"),
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="utilizador", type="object"),
     *             @OA\Property(property="token", type="string", description="Token de acesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Email não verificado",
     *         @OA\JsonContent(
     *             @OA\Property(property="erro", type="string", description="Email não verificado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Email ou password incorretos",
     *         @OA\JsonContent(
     *             @OA\Property(property="erro", type="string", description="Email ou password incorretos")
     *         )
     *     )
     * )
     */
    function login(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $utilizador = Utilizador::where('email', $request->email)->first();

        if ($utilizador == null) {
            return response(['message' => __('custom.credenciais_erradas')], 422);
        }

        if (!Hash::check(($request->password), $utilizador->password)) {
            return response(['message' => __('custom.credenciais_erradas')], 422);
        }

        if (!$utilizador->hasVerifiedEmail()) {
            return response(['message' => __('custom.email_nao_verificado')], 403);
        }

        //apaga tokens anteriores e cria um novo
        $utilizador->tokens()->delete();
        $token = $utilizador->createToken($utilizador->email);

        //retorna o token
        return response(['utilizador' => new UtilizadorResource($utilizador), 'token' => $token->plainTextToken], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/registar",
     *     summary="Registar",
     *     description="Regista um novo utilizador.",
     *     tags={"Utilizadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Dados de registo",
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Nome do Utilizador"),
     *             @OA\Property(property="email", type="string", format="email", example="example@example.com"),
     *             @OA\Property(property="password", type="string", example="password"),
     *             @OA\Property(property="tipo", type="integer", example="1")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registo realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="sucesso", type="string", description="Registo realizado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Erro de validação"),
     *             @OA\Property(property="errors", type="object", description="Detalhes dos erros de validação")
     *         )
     *     )
     * )
     */
    function registar(Request $request)
    {

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores',
            'password' => 'required|string|min:8',
            'tipo' => 'required|numeric',
        ]);

        //Criar novo objeto Utilizador
        $utilizador = new Utilizador();
        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];
        $utilizador->password = Hash::make($validated['password']);
        $utilizador->tipo = $validated['tipo'];

        //Guardar na  base de dados
        $utilizador->save();

        Event(new Registered($utilizador));

        return response(['message' => __('custom.registo_sucesso')], 200);
    }


    function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response(['message' => __('custom.logout_sucesso')], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/associacoes",
     *     summary="Listar Associações",
     *     description="Retorna uma lista de associações.",
     *     tags={"Utilizadores"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Lista de associações",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="associacoes"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Não autorizado")
     *         )
     *     )
     * )
     */
    function listarAssociacoes(Request $request)
    {
        //return todos os utilizadores que sao do tipo 2
        $utilizadores = Utilizador::where('tipo', 2)->get();
        return response(['associacoes' => UtilizadorResource::collection($utilizadores)], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/perfil/{id}",
     *     summary="Perfil do Utilizador",
     *     description="Retorna o perfil de um utilizador pelo seu ID.",
     *     tags={"Utilizadores"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID do utilizador",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil do utilizador",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="utilizador"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Não autorizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilizador não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="erro", type="string", description="Utilizador não encontrado")
     *         )
     *     )
     * )
     */
    function perfilUtilizador(Request $request)
    {
        $utilizador = Utilizador::where('id', $request->id)->first();
        if ($utilizador == null)
            return response(['message' => __('custom.utilizador_nao_encontrado')], 404);
        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/editarperfil",
     *     summary="Alterar Perfil do Utilizador Particular",
     *     description="Atualiza o perfil do utilizador autenticado.",
     *     tags={"Utilizadores"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", description="Nome do utilizador"),
     *             @OA\Property(property="email", type="string", format="email", description="Email do utilizador"),
     *             @OA\Property(property="telefone", type="string", description="Telefone do utilizador"),
     *             @OA\Property(property="fotografia", type="string", format="binary", description="Fotografia do utilizador"),
     *             @OA\Property(property="localizacao", type="string", description="Localização do utilizador"),
     *             @OA\Property(property="distrito", type="string", description="Distrito do utilizador"),
     *             @OA\Property(property="codigo_postal", type="string", description="Código postal do utilizador")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil do utilizador atualizado",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="utilizador"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Não autorizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilizador não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="erro", type="string", description="Utilizador não encontrado")
     *         )
     *     )
     * )
     */
    function alterarPerfil(Request $request)
    {
        $utilizador =  $request->user();
        if ($utilizador == null)
            return response(['erro' => __('custom.utilizador_nao_encontrado')], 404);

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores,email,' . $utilizador->id,
            'telefone' => 'string',
            'fotografia' => 'file',
            'localizacao' => 'string',
            'distrito' => 'string',
            'codigo_postal' => 'string'
        ]);

        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];

        if ($request->telefone != null) {
            $utilizador->telefone = $request->telefone;
        }

        if ($request->localizacao != null) {
            $utilizador->localizacao = $request->localizacao;
        }

        if ($request->distrito != null) {
            $utilizador->distrito = $request->distrito;
        }

        if ($request->codigo_postal != null) {
            $utilizador->codigo_postal = $request->codigo_postal;
        }

        if ($request->fotografia != null) {
            $nomeFotografia = $utilizador->id . $utilizador->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/utilizadores/'), $nomeFotografia);
            $utilizador->fotografia = $nomeFotografia;
        }
        //Guardar na  base de dados
        $utilizador->save();

        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/editarperfilA",
     *     summary="Alterar Perfil da Associação",
     *     description="Atualiza o perfil da associação (utilizador autenticado como uma associação).",
     *     tags={"Utilizadores"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", description="Nome da associação"),
     *             @OA\Property(property="email", type="string", format="email", description="Email da associação"),
     *             @OA\Property(property="telefone", type="string", description="Telefone da associação"),
     *             @OA\Property(property="fotografia", type="string", format="binary", description="Fotografia da associação"),
     *             @OA\Property(property="localizacao", type="string", description="Localização da associação"),
     *             @OA\Property(property="website", type="string", description="Website da associação"),
     *             @OA\Property(property="facebook", type="string", description="Página do Facebook da associação"),
     *             @OA\Property(property="instagram", type="string", description="Perfil do Instagram da associação"),
     *             @OA\Property(property="horario", type="string", description="Horário de funcionamento da associação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil da associação atualizado",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="utilizador"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Não autorizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilizador não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="erro", type="string", description="Utilizador não encontrado")
     *         )
     *     )
     * )
     */
    function alterarPerfilAssociacao(Request $request)
    {
        $utilizador =  $request->user();
        if ($utilizador == null)
            return response(['message' => __('custom.utilizador_nao_encontrado')], 404);

        //Validar os dados que recebo
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:utilizadores,email,' . $utilizador->id,
            'telefone' => 'string',
            'fotografia' => 'file',
            'localizacao' => 'string',
            'website' => 'string',
            'facebook' => 'string',
            'instagram' => 'string',
            'horario' => 'json'
        ]);

        $utilizador->nome = $validated['nome'];
        $utilizador->email = $validated['email'];

        if ($request->telefone != null) {
            $utilizador->telefone = $request->telefone;
        }

        if ($request->localizacao != null) {
            $utilizador->localizacao = $request->localizacao;
        }

        if ($request->fotografia != null) {
            $nomeFotografia = $utilizador->id . $utilizador->nome . '.' . $request->fotografia->extension();
            $request->fotografia->move(public_path('storage/img/utilizadores/'), $nomeFotografia);
            $utilizador->fotografia = $nomeFotografia;
        }

        if ($request->website != null) {
            $utilizador->website = $request->website;
        }

        if ($request->facebook != null) {
            $utilizador->facebook = $request->facebook;
        }

        if ($request->instagram != null) {
            $utilizador->instagram = $request->instagram;
        }

        if ($request->horario != null) {
            $utilizador->horario = $request->horario;
        }

        if($request->iban != null) {
            $utilizador->iban = $request->iban;
        }


        //Guardar na  base de dados
        $utilizador->save();

        return response(['utilizador' => new UtilizadorResource($utilizador)], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/forgot-password",
     *     summary="Esqueceu a password",
     *     description="Envia um link de redefinição de password para o email fornecido.",
     *     tags={"Utilizadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", description="Email do utilizador")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Link de redefinição de password enviado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Link de redefinição de password enviado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Os dados fornecidos são inválidos")
     *         )
     *     )
     * )
     */
    function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return  $status === Password::RESET_LINK_SENT ? response(['message' => 'OK'], 200) : response(['message' => __($status)], 422);
    }

    /**
     * @OA\Post(
     *     path="/api/reset-password",
     *     summary="Redefinir password",
     *     description="Redefine a password do utilizador com base no token de redefinição e no email fornecidos.",
     *     tags={"Utilizadores"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", description="Token de redefinição da password"),
     *             @OA\Property(property="email", type="string", format="email", description="Email do utilizador"),
     *             @OA\Property(property="password", type="string", format="password", description="Nova password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", description="Confirmação da nova password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password redefinida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Password redefinida com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Os dados fornecidos são inválidos")
     *         )
     *     )
     * )
     */
    function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return  $status === Password::PASSWORD_RESET ? response(['message' => 'OK'], 200) : response(['message' => __($status)], 422);
    }

    /**
     * @OA\Get(
     *     path="/api/email/verify/{id}/{hash}",
     *     summary="Verificar email",
     *     description="Verifica o email de um utilizador com base no ID do utilizador e no hash fornecidos.",
     *     tags={"Utilizadores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID do utilizador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="hash",
     *         in="query",
     *         required=true,
     *         description="Hash para verificação do email",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email verificado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Email verificado com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email não verificado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Email não verificado")
     *         )
     *     )
     * )
     */
    function verificaEmail(Request $request)
    {
        $utilizador = Utilizador::where('id', $request->id)->first();

        if (hash_equals(sha1($utilizador->getEmailForVerification()), $request->hash)) {
            $utilizador->markEmailAsVerified();

            return redirect('https://petship.pt/login')->with('message', __('custom.email_verificado'));
        }

        abort(404, __('custom.email_nao_verificado'));
    }

    /**
     * @OA\Delete(
     *     path="/api/eliminarconta/{id}",
     *     summary="Eliminar conta",
     *     description="Elimina a conta de um utilizador com base no ID do utilizador.",
     *     tags={"Utilizadores"},
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         required=true,
     *         description="ID do utilizador",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Conta eliminada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Conta eliminada com sucesso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilizador não encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", description="Utilizador não encontrado")
     *         )
     *     )
     * )
     */
    function eliminarConta(Request $request)
    {
        $utilizador = Utilizador::where('id', $request->id)->first();
        if ($utilizador == null)
            return response(['message' => __('custom.utilizador_nao_encontrado')], 404);

        $utilizador->delete();
        return response(['message' => __('custom.conta_eliminada')], 200);
    }
}
