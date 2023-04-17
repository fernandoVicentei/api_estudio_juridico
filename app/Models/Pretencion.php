<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pretencion extends Model
{
    use HasFactory;
    protected $table = "pretensiones";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'descripcion',
        'fecha',
        'valorMedida'
    ];

    
    public function detallepretencion()
    {
        return $this->hasOne(Detallepretencion::class, 'pretencion_id');
    }

    public function proceso()
    {
        return $this->hasOne(Proceso::class, 'pretenciones_id'  );
    }
}

