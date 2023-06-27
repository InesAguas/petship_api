<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $table = 'mensagens';

         /**
 * @OA\Schema(
 *    schema="Mensagem",
 *    @OA\Property(
 *        property="id",
 *        type="integer",
 *        description="ID da mensagem",
 *        nullable=false,
 *        example="1"
 *    ),
 *   @OA\Property(
 *       property="id_envia",
 *      type="integer",
 *     description="ID do utilizador que enviou a mensagem",
 *    nullable=false,
 *   example="1"
 * ),
 * @OA\Property(
 *    property="id_recebe",
 *   type="integer",
 * description="ID do utilizador que recebeu a mensagem",
 * nullable=false,
 * example="2"
 * ),
 * @OA\Property(
 *   property="mensagem",
 * type="string",
 * description="Mensagem enviada",
 * nullable=false,
 * example="Olá, tudo bem?"
 * ),
 * )
 * 
 *  @return array
 */
    protected $fillable = [
        'id_envia',
        'id_recebe',
        'mensagem' 
    ];

}
