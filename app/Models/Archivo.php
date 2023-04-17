<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivo extends Model
{
    use HasFactory;
    protected $table = "archivos";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'rutaArchivo',
        'fecha'
    ];

   
    public function procesos()
    {
        return $this->belongsToMany(Proceso::class, 'procesos_id');
    }
    
}
