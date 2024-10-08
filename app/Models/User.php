<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'cnic',
        'image',
        'mobile_no',
        'password',
        'is_admin',
        'salary',
        'empleado_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                      ->orWhere('email', 'LIKE', '%' . $search . '%');
            });
        }
        return $query;
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function accounts()
    {
        return $this->hasMany(Account::class, 'user_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'user_id');
    }

    public function certificates()
    {
        return $this->hasMany(UserCertification::class, 'user_id');
    }

    public function details()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }
    public function imports()
    {
        return $this->hasMany(ImportCsvDetail::class, 'user_id');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(UserLoan::class, 'user_id');
    }

    public function fines(): HasMany
    {
        return $this->hasMany(Fine::class, 'user_id');
    }


    public function cafeExpense(): HasMany
    {
        return $this->hasMany(CafeExpense::class, 'user_id');
    }

    public function wfh(): HasMany
    {
        return $this->hasMany(WorkFromHome::class, 'user_id');
    }
}
