<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use Billable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    /**
     * Get the user by email.
     */
    public static function getUserByEmail(string $email)
    {
        //dd($email);
        //dd(self::where('email', $email)->first());
        return self::where('email', $email)->first();
    }

    /**
     * Return the user specified by the provided username.
     *
     * @param string $username
     * @return mixed
     */
    public static function getUserByUsername(string $username)
    {
        return self::where('username', $username)->first();
    }
    /**
     * Check if the user is active.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
    public function scopeWhereRole(Builder $query, string $role): Builder
    {

        $query->whereHas(
            'roles',
            function ($q) use ($role) {
                return $q->where('name', $role);
            }
        );
        return $query;
    }

    public function scopeWhereRoleNot(Builder $query, array $role): Builder
    {
        $query->whereDoesntHave(
            'roles',
            fn ($q) => $q->whereIn('name', $role)
        );
        return $query;
    }
    public function getImage()
    {
        return $this->avatar ? Storage::url('user/' . $this->avatar) : asset("assets/img/avatars/1.png");
        // return $this->avatar ? Storage::url('user/' . $this->avatar) : asset('dummy.jpeg');
    }

    public function isParent(): bool
    {
        return $this->hasRole('Parent');
    }
    public function getName()
    {
        return $this->name ? $this->name : $this->first_name . ' ' . $this->last_name;
    }
    public function units()
    {
        return $this->hasMany(UserUnit::class);
    }
    public function getStatus()
    {
        $status = '';

        if ($this->status == 'active') {
            $status = "<span class='badge rounded-pill bg-success bg-glow'>Active</span>";
        } elseif ($this->status == 'inactive') {
            $status = "<span class='badge rounded-pill bg-danger bg-glow'>In-Active</span>";
        }
        return $status;
    }
    public function scopeByName($query, $name)
    {
        if (isset($name)) {
            return $query->orWhere('first_name', 'LIKE', '%' . $name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $name . '%');
        }
        return $query;
    }
    public function scopeByEmail($query, $email)
    {
        if (isset($email)) {
            return $query->where('email', 'LIKE', '%' . $email . '%');
        }
        return $query;
    }
    public function scopeByPhone($query, $phone)
    {
        if (isset($phone)) {
            return $query->where('phone', 'LIKE', '%' . $phone . '%');
        }
        return $query;
    }
    public function scopeByStatus($query, $status)
    {
        if (isset($status)) {
            return $query->where('status', 'LIKE', '%' . $status . '%');
        }
        return $query;
    }
}
