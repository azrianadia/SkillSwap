<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config;

#[Fillable(['name', 'email', 'password', 'prodi', 'semester', 'whatsapp_number', 'avatar', 'plan', 'swap_quota', 'quota_reset_at', 'is_pro', 'midtrans_customer_id', 'midtrans_subscription_id', 'badge', 'support_level'])]
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
            'quota_reset_at' => 'datetime',
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Subscription Methods
    
    public function hasSwapQuota(): bool
    {
        return $this->is_pro || $this->swap_quota > 0;
    }

    public function useSwapQuota(): bool
    {
        if ($this->is_pro) {
            return true;
        }
        if ($this->swap_quota <= 0) {
            return false;
        }
        $this->decrement('swap_quota');
        return true;
    }

    public function refundSwapQuota(): void
    {
        if ($this->is_pro) {
            return;
        }
        $this->increment('swap_quota');
    }

    public function resetMonthlyQuota(): void
    {
        if ($this->is_pro) {
            return;
        }
        $this->update([
            'swap_quota' => Config::get('subscription.plans.free.swap_limit', 5),
            'quota_reset_at' => now()->addMonth(),
        ]);
    }

    public function isSubscriptionActive(): bool
    {
        if (!$this->is_pro) {
            return false;
        }
        return $this->quota_reset_at && $this->quota_reset_at->isFuture();
    }

    public function getQuotaInfo(): array
    {
        $freeLimit = Config::get('subscription.plans.free.swap_limit', 5);
        
        return [
            'plan' => $this->plan,
            'is_pro' => $this->is_pro,
            'limit' => $this->is_pro ? 'Unlimited' : $freeLimit,
            'remaining' => $this->is_pro ? 'Unlimited' : max(0, $this->swap_quota),
            'used' => $this->is_pro ? 0 : ($freeLimit - $this->swap_quota),
            'reset_at' => $this->quota_reset_at,
            'formatted_reset' => $this->quota_reset_at?->format('d M Y'),
        ];
    }

    public function isPro(): bool
    {
        return $this->is_pro;
    }
}