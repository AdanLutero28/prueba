<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TCalificacione extends Model
{
    protected $fillable = ['id_t_materias','id_t_usuarios','calificacion', 'fecha_registro'];
    public $timestamps = false;
}
