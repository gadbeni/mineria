<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form101 extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'verification_token',
        'certificate_id',
        'typeMineral_id',
        'leyMineral',
        'pesoBruto',
        'humedad',
        'pesoNeto',
        'lote',
        'municipio',
        'localidad',
        'codigoAreaMinero',
        'nombreAreaMinero',
        'medioTransporte',
        'unidaddemedida1',
        'origen',
        'intermedio',
        'final',
        'matricula',
        'nombreConductor',
        'licenciaConducir',
        'nombreEncargadoTrasporte',
        'ciEncargadoTrasporte',
        'register_id',
        'deleted_id',
        'delete_reason',
        'observation',
        'confirmado',
        'confirmed_at',
        'confirmed_by',
        'signature_id',
    ];

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    public function typeMineral()
    {
        return $this->belongsTo(TypeMineral::class, 'typeMineral_id');
    }

    public function signature()
    {
        return $this->belongsTo(\App\Models\Signature::class, 'signature_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'confirmed_by');
    }
}
