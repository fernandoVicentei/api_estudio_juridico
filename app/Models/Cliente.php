<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $table = "clientes";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'estado',
        'persona_id',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id');
    }

    
    public function procesos()
    {
        return $this->hasMany(Proceso::class, 'cliente_id');
    }




}
