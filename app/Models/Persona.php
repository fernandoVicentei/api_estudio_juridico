<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $table = "persona";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'nombre',
        'apellido1',
        'apellido2',
        'cedula',
        'celular',
        'direccion',
        'fechaNacimiento',
        'genero',
        'estadoCivil',
        'tipoPersona_id'
    ];

    public function abogado(){             
       return $this->hasOne(Abogado::class, 'persona_id');
       
    }
    public function cliente(){             
        return $this->hasOne(Cliente::class, 'persona_id');
        
    }

   
    public function tipopersona()
    {
        return $this->belongsTo(Tipopersona::class, 'tipoPersona_id', 'id');
    }


}
