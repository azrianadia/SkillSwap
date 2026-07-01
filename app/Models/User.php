<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'prodi', 'semester', 'whatsapp_number'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')->withPivot('type', 'proficiency_level')->withTimestamps();
    }

    public function offeredSkills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->wherePivot('type', 'offer')
            ->withPivot('id', 'proficiency_level')
            ->withTimestamps();
    }

    public function soughtSkills()
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->wherePivot('type', 'seek')
            ->withPivot('id', 'proficiency_level')
            ->withTimestamps();
    }

    public function sentSwaps()
    {
        return $this->hasMany(Swap::class, 'sender_id');
    }

    public function receivedSwaps()
    {
        return $this->hasMany(Swap::class, 'receiver_id');
    }

    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'reviewer_id');
    }

    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    public function getAverageRating()
    {
        return $this->reviewsReceived()->avg('rating') ?? 0;
    }

    public function getTotalSwaps()
    {
        return $this->sentSwaps()->where('status', 'accepted')->count() + 
               $this->receivedSwaps()->where('status', 'accepted')->count();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function unreadMessagesCount()
    {
        return $this->receivedMessages()->unread()->count();
    }
}
