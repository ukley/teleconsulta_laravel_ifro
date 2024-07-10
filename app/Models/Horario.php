<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $table = "horarios";
    protected $fillable = [
        'paciente',
        'email',
        'url_consulta',
        'finalizada',
        'teleconsulta',
        'id_agenda',
        'retorno',
        'data',
        'nome_medico',
        'tipo_consulta',
        'horario',
        'UsuarioId',
        'sms'
    ];

    use HasFactory;
}
