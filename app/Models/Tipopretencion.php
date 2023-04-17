<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipopretencion extends Model
{
    use HasFactory;
    protected $table = "tipopretencion";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
       'pretencion'
    ];

    
    public function detallepretenciones()
    {
        return $this->hasMany(Detallepretencion::class, 'tipopretencion_id');
    }
}

