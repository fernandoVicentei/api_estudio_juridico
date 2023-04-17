<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archivoproceso extends Model
{
    use HasFactory;
    protected $table = "archivosprocesos";
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'archivos_id',
        'procesos_id',
        'documentos_id',
    ];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documentos_id', 'id');
    }


}
