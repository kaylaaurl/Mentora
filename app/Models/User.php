<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable 
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'bio',
        'education_level',
        'subjects',
        'hourly_rate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'subjects' => 'array',
        'hourly_rate' => 'decimal:2',
    ];

    
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function clientProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id');
    }

    public function freelancerProjects(): HasMany
    {
        return $this->hasMany(Project::class, 'freelancer_id');
    }

    public function tutoringSessions(): HasMany
    {
        return $this->hasMany(TutoringSession::class, 'tutor_id');
    }

    public function learningSessions(): HasMany
    {
        return $this->hasMany(TutoringSession::class, 'tutee_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'tutor_id');
    }

    public function isTutor(): bool
    {
        return $this->role === 'tutor';
    }

    public function isTutee(): bool
    {
        return $this->role === 'tutee';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
