<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\returnSelf;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'signature_id',
        'miningOperator',
        'code',
        'dateStart',
        'dateFinish',
        'status',
        'registerUser_id',
        'deleted_at'
    ];

    public function code()
    {
        return $this->belongsTo(Code::class, 'code_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class, 'signature_id');
    }
}

