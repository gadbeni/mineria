<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form101Edit extends Model
{
    use HasFactory;

    protected $table = 'form101_edits';

    protected $fillable = [
        'form101_id',
        'before',
        'after',
        'changed',
        'unchanged',
        'edited_by',
        'edited_at',
    ];

    protected $casts = [
        'before'    => 'array',
        'after'     => 'array',
        'changed'   => 'array',
        'unchanged' => 'array',
        'edited_at' => 'datetime',
    ];

    public function form101()
    {
        return $this->belongsTo(Form101::class, 'form101_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'edited_by');
    }
}
