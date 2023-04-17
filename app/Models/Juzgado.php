<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juzgado extends Model
{
    use HasFactory;
    protected $table = "juzgados";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'direccion',
        'nombre',
        
    ];

    public function procesos()
    {
        return $this->hasMany(Proceso::class, 'juzgado_id' );
    }

}

