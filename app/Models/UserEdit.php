<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEdit extends Model
{
    use HasFactory;

    protected $table = 'user_edits';

    protected $fillable = [
        'user_id',
        'action',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function editedBy()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}
