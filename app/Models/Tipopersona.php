<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipopersona extends Model
{
    use HasFactory;
    protected $table = "tipopersona";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'tipo'
    ];

 
    public function persona()
    {
        return $this->hasOne(Persona::class, 'tipoPersona_id');
    }
}
