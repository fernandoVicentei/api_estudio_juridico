<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleproceso extends Model
{
    use HasFactory;
    protected $table = "detalleprocesos";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',        
        'declaracionDemandante',
        'declaracionDemandado',
        'procesos_id'
    ];

  
    public function proceso()
    {
        return $this->belongsTo(Proceso::class, 'procesos_id', 'id');
    }

}
