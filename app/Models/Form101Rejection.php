<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form101Rejection extends Model
{
    use HasFactory;

    protected $table = 'form101_rejections';

    protected $fillable = [
        'form101_id',
        'reject_reason',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'rejected_at' => 'datetime',
    ];

    public function form101()
    {
        return $this->belongsTo(Form101::class, 'form101_id');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'rejected_by');
    }
}
