<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'alias',
        'first_name',
        'last_name',
        'job',
        'status',
        'registerUser_id',
        'deleted_at'
    ];
}
