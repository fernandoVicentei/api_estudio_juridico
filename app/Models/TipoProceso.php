<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoProceso extends Model
{
    use HasFactory;
    protected $table = "tipoproceso";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
       'proceso',
       'descripcion'
    ];
    
    public function procesos()
    {
        return $this->hasMany(Proceso::class, 'tipoproceso_id'  );
    }
    
}

