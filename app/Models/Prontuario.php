<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prontuario extends Model
{
    protected $table = "prontuario";
    protected $fillable = ['id_paciente', 'id_medico','status','descricao'];
    use HasFactory;
}
