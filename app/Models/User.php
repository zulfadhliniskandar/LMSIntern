<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, Impersonate;

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function isAdmin(): bool
    {
        return (bool) $this->admin;
    }

    public function isNormalUser(): bool
    {
        return !$this->admin && 
               !$this->instructor && 
               !$this->student;
    }

    public function getIsSuperAdminAttribute(): bool
    {
        return $this->isAdmin();
    }

    public function isStudent(): bool
    {
        return (bool) $this->student;
    }

    public function isInstructor(): bool
    {
        return (bool) $this->instructor;
    }

    /**
     * Auto-assign Admin role when creating user via CLI (php artisan make:filament-user)
     */
    protected static function booted(): void
    {
        static::created(function (User $user) {
            if (app()->runningInConsole()) {
                Admin::firstOrCreate(['user_id' => $user->id]);
            }
        });
    }

    /**
     * Determine if the user can access the Filament Admin Panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return (bool) $this->admin;
    }

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
     * Get the user's initials
     */
    public function initials(): string
    {
        $initials = Str::initials($this->name, true);

        return Str::length($initials) > 1
            ? Str::substr($initials, 0, 1).Str::substr($initials, -1)
            : $initials;
    }

    public function canImpersonate(): bool
    {
        return $this->isAdmin() === true;
    }

    public function canBeImpersonated(): bool
    {
        return $this->isAdmin() !== true;
    }
}
