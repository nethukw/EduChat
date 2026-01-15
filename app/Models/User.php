<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /* =====================================================
     | ROLE CONSTANTS (NO MAGIC NUMBERS)
     ===================================================== */
    public const ROLE_STUDENT = 0;
    public const ROLE_TEACHER = 1;
    public const ROLE_ADVISOR = 2;
    public const ROLE_ADMIN   = 3;

    /* =====================================================
     | MASS ASSIGNMENT
     ===================================================== */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'points',
        'academic_level',
        'skills',
        'interests',
        'avatar',
        'google_id',
        'github_id',
        'phone',
        'bio',
        'location',
    ];


    /* =====================================================
     | HIDDEN FIELDS
     ===================================================== */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* =====================================================
     | ATTRIBUTE CASTS
     | skills & interests are now ARRAYS (IMPORTANT)
     ===================================================== */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'skills'            => 'array',
            'interests'         => 'array',
            'points'            => 'integer',
            'role'              => 'integer',
        ];
    }

    /* =====================================================
     | ACCESSORS (FOR CLEAN BLADE CODE)
     ===================================================== */

    // Usage: $user->role_name
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            self::ROLE_STUDENT => 'Student',
            self::ROLE_TEACHER => 'Teacher',
            self::ROLE_ADVISOR => 'Advisor',
            self::ROLE_ADMIN   => 'Admin',
            default            => 'Unknown',
        };
    }

    // Usage: <img src="{{ $user->avatar_url }}">
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name='
            . urlencode($this->name)
            . '&background=fff&color=6366f1';
    }

    /* =====================================================
     | POINTS SYSTEM
     ===================================================== */

    /**
     * Add points to the user and check for promotions
     */
    public function addPoints(int $amount): ?string
    {
        // Prevent negative values
        if ($amount <= 0) {
            return null;
        }

        $this->points = ($this->points ?? 0) + $amount;
        $this->save();

        return $this->checkForPromotion();
    }

    /**
     * Promote user based on points
     */
    protected function checkForPromotion(): ?string
    {
        if (
            $this->role === self::ROLE_STUDENT &&
            $this->points >= 1000
        ) {
            $this->role = self::ROLE_TEACHER;
            $this->save();

            return "🎉 Congratulations! You have been promoted to Teacher!";
        }

        return null;
    }

    //profile completeness percentage
    public function profileCompletion(): array
    {
        // Admins are excluded
        if ($this->role === self::ROLE_ADMIN) {
            return [
                'percentage' => 100,
                'checks'     => [],
                'missing'    => collect(),
            ];
        }

        $checks = [
            'Name'           => !empty($this->name),
            'Email'          => !empty($this->email),
            'Phone Number'   => !empty($this->phone),
            'Academic Level' => !empty($this->academic_level),
            'Skills'         => is_array($this->skills) && count($this->skills) > 0,
            'Interests'      => is_array($this->interests) && count($this->interests) > 0,
            'Profile Photo'  => !empty($this->avatar),
            'Bio'            => !empty($this->bio),
            'Location' => !empty($this->location),
        ];

        $completed = collect($checks)->filter()->count();
        $total = count($checks);

        return [
            'percentage' => (int) round(($completed / $total) * 100),
            'checks'     => $checks,
            'missing'    => collect($checks)->filter(fn($v) => ! $v)->keys(),
        ];
    }
}
