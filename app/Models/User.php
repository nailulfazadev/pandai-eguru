<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'is_admin', 'password', 'phone', 'nip', 'school_name', 'principal_name', 'principal_nip', 'subscription_tier', 'subscription_ends_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_admin' => 'boolean',
            'password' => 'hashed',
            'subscription_ends_at' => 'datetime',
        ];
    }

    /**
     * Get the documents created by the user.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function hasActiveSubscription()
    {
        if (!$this->subscription_ends_at) {
            return false;
        }
        return now()->lessThanOrEqualTo($this->subscription_ends_at);
    }

    public function isTrial()
    {
        return $this->subscription_tier === 'trial';
    }

    public function generationsToday()
    {
        return $this->documents()->whereDate('created_at', now()->toDateString())->count();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }
}
