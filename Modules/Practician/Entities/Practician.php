<?php

namespace Modules\Practician\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Modules\Address\Entities\Address;
use Modules\Appointment\Entities\Appointment;
use Modules\Chat\Entities\Chat;
use Modules\Core\Entities\Commune;
use Modules\Document\Entities\Document;
use Modules\Establishment\Entities\Establishment;
use Modules\Motif\Entities\Motif;
use Modules\Notes\Entities\Note;
use Modules\Signature\Entities\Signature;
use Modules\Speciality\Entities\Speciality;
use Modules\TimeSlot\Entities\TimeSlot;
use Modules\User\Filters\UserFilters;
use Modules\User\Traits\HasProfilePhoto;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Practician extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasRoles,
        HasProfilePhoto,
        Notifiable,
        HasPermissions,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birthdate',
        'phone_number',
        'timezone',
        'language',
        'last_login_ip',
        'last_login_at',
        'speciality',
        'is_active',
        'is_valid',
        'address',
        'accepts_new_patients',
        'presentation',
        'expertises',
        'profile_photo_url',
        'slug',
        'emergency',
        'head_quarter',
        'device_token',
        'otp_code',
        'phone_number_verified_at',
        'email_verified_at',
    ];

    protected static function boot()
    {
        parent::boot();
        static::created(function ($user) {
            $user->slug = $user->generateSlug($user->first_name." ".$user->last_name. " ".$user->id);
            $user->save();
        });
    }

    private function generateSlug($name)
    {
        if (static::whereSlug($slug = Str::slug($name))->exists()) {
            $max = static::whereName($name)->latest('id')->skip(1)->value('slug');
            if (isset($max[-1]) && is_numeric($max[-1])) {
                return preg_replace_callback('/(\d+)$/', function($mathces) {
                    return $mathces[1] + 1;
                }, $max);
            }
            return "{$slug}-2";
        }
        return $slug;
    }
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
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'is_valid' => 'boolean',
        'accepts_new_patients'=> 'boolean',
        'expertises' => 'array'
    ];

    public function isAdminPractician(): bool
    {
        return $this->hasRole('adminpractician');
    }

    public function isPractician(): bool
    {
        return $this->hasRole('practician');
    }

    public function establishments(): BelongsToMany
    {
        return $this->belongsToMany(Establishment::class);
    }

    public function establishmentData(): HasOne
    {
        return $this->hasOne(Establishment::class, 'admin_practician', 'id');
    }

    public function specialityData(): BelongsTo
    {
        return $this->belongsTo(Speciality::class, 'speciality', 'id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function transfers()
    {
        return $this->belongsToMany(
            Appointment::class,
            'transfers',
            'practician_id',
            'appointment_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class,'created_by','id');
    }

    public function motifs()
    {
        return $this->hasMany(Motif::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function chats()
    {
        return $this->belongsToMany(Chat::class);
    }

    public function availableSlots($date = null, $period = null)
    {
        if ($period) {
            $startDate = explode(';', $period)[0];
            $endDate = explode(';', $period)[1];

            return $this->timeSlots()
                ->select(['payload->date as date','payload->start_time as start_time','payload->end_time as end_time','status','establishment_id','id'])
                ->whereBetween('payload->date', [$startDate, $endDate])
                ->where('appointment_id', null)
                ->get()
                ->groupBy('date');
        }
        $date ?? ($date = now()->subWeeks(2)->toDateString());
        return $this->timeSlots()
            ->select(['payload->date as date','payload->start_time as start_time','payload->end_time as end_time','status','establishment_id','id'])
            ->where('payload->date', '>=', $date)
            ->where('appointment_id', null)
            ->get()
            ->groupBy('date');
    }

    public function unavailableSlots($date = null, $period = null)
    {
        if ($period) {
            $startDate = explode(';', $period)[0];
            $endDate = explode(';', $period)[1];

            return $this->timeSlots()
                ->select(['payload->date as date','payload->start_time as start_time','payload->end_time as end_time','status','establishment_id','appointment_id','id'])
                ->whereBetween('payload->date', [$startDate, $endDate])
                ->where('appointment_id', '!==', null)
                ->get()
                ->groupBy('date');
        }

        $date ?? ($date = now()->subWeeks(2)->toDateString());
        return $this->timeSlots()->with('appointment')
            ->select(['payload->date as date','payload->start_time as start_time','payload->end_time as end_time','status','establishment_id','appointment_id','id'])
            ->where('payload->date', '>=', $date)
            ->where('appointment_id', '!=', null)
            ->get()
            ->groupBy('date');
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

    public function signature()
    {
        return $this->hasOne(Signature::class);
    }

    public function scopeFilter(Builder $query, Request $request, array $filters = []): Builder
    {
        return (new UserFilters($request))->add($filters)->filter($query);
    }
}
