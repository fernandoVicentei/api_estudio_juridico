<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    use HasFactory;
    protected $table = "procesos";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'fecha',
        'fechaSucesos',
        'estado',
        'hechosOcurridos',
        'abogado_id',
        'cliente_id',
        'tipoproceso_id',
        'juzgado_id',

    ];

    
    public function archivos()
    {
        return $this->belongsToMany(Archivo::class, 'archivos_id'  );
    }
    public function detalleproceso()
    {
        return $this->hasOne(Detalleproceso::class, 'procesos_id' );
    }
    public function abogado()
    {
        return $this->belongsTo(Abogado::class, 'abogado_id', 'id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }
    public function tipoproceso()
    {
        return $this->belongsTo(TipoProceso::class, 'tipoproceso_id','id');
    }
    public function juzgado()
    {
        return $this->belongsTo(Juzgado::class, 'juzgado_id', 'id');
    }

    public function cita()
    {
        return $this->hasOne(Citas::class, 'proceso_id' );
    }

   /*  public function pretencion()
    {
        return $this->belongsTo(Pretencion::class, 'pretenciones_id', 'id');
    }
 */


}

