<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detallepretencion extends Model
{
    use HasFactory;
    protected $table = "detallepretension";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'fechaDeclaracion',
        'detallePretencionDemandante',
        'datallePretencionDemandado',
        'pretencion_id',
        'tipopretencion_id'
    ];
    
    public function pretencion()
    {
        return $this->belongsTo(Pretencion::class, 'pretencion_id', 'id');
    }
    public function tipopretencion()
    {
        return $this->belongsTo(Tipopretencion::class, 'tipopretencion_id', 'id');
    }


}
