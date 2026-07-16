<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    protected $dates = ['deleted_at'];

    /**
     * Campos auditados en el historial de modificaciones (columna => etiqueta).
     */
    protected static $auditFields = [
        'name'       => 'Nombre',
        'email'      => 'Email',
        'password'   => 'Contraseña',
        'role_id'    => 'Rol',
        'company_id' => 'Empresa',
        'status'     => 'Estado',
        'avatar'     => 'Avatar',
    ];

    /**
     * Campos auditados (public para el Observer).
     */
    public static function auditFields()
    {
        return self::$auditFields;
    }

    /**
     * Convierte el valor de un campo auditado a texto legible para el historial.
     */
    public static function displayAudit($key, $value)
    {
        // La contraseña nunca se guarda ni se muestra; solo se enmascara.
        if ($key === 'password') {
            return '••••••••';
        }

        if ($value === null || $value === '') {
            return '—';
        }

        switch ($key) {
            case 'status':
                return ((int) $value === 1) ? 'Activo' : 'Inactivo';
            case 'role_id':
                $role = \TCG\Voyager\Models\Role::find($value);
                return $role ? ($role->display_name ?: $role->name) : ('Rol #' . $value);
            case 'company_id':
                $company = \App\Models\Company::withTrashed()->find($value);
                return $company ? $company->razon : ('Empresa #' . $value);
            default:
                return (string) $value;
        }
    }

    public function edits()
    {
        return $this->hasMany(\App\Models\UserEdit::class, 'user_id')->orderBy('edited_at', 'desc');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'company_id',
        'status',
        'deleted_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
