<?php

namespace Modules\User\Entities;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Antecedent\Entities\Antecedent;
use Modules\Appointment\Entities\Appointment;
use Modules\User\Filters\UserFilters;
use Modules\User\Traits\HasProfilePhoto;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        HasFactory,
        HasRoles,
        HasProfilePhoto,
        Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'email',
        'password',
        'birthdate',
        'phone_number',
        'timezone',
        'language',
        'last_login_ip',
        'last_login_at',
        'can_login',
        'parent_id',
        'status',
        'address',
        'profile_photo_url',
        'otp_code',
        'weight',
        'height',
        'created_by',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var string[]|bool
     */
    protected $guarded = [];

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
        'phone_number_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'can_login' => 'boolean',
        'status' => 'boolean',
    ];

    public function isRoot(): bool
    {
        return $this->hasRole('root');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin|root');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function isPatient(): bool
    {
        return $this->hasRole('patient');
    }

    public function antecedents(): HasMany
    {
        return $this->hasMany(Antecedent::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id', 'id');
    }

    public function relatives(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public function userRoles(): array
    {
        $roles = array();
        foreach ($this->roles as $role) {
            $roles[] = $role->name;
        }
        return $roles;
    }

    public function userPermissions(): array
    {
        $permissions = array();
        foreach ($this->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissions[] = $permission->name;
            }
        }
        return $permissions;
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new UserFilters($request))->add($filters)->filter($query);
    }
}
