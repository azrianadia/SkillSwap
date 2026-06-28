<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['skill_name', 'category'];

    public function userSkills()
    {
        return $this->hasMany(UserSkill::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_skills')->withPivot('type', 'proficiency_level')->withTimestamps();
    }

    public function offeredByUsers()
    {
        return $this->belongsToMany(User::class, 'user_skills')->wherePivot('type', 'offer')->withPivot('proficiency_level')->withTimestamps();
    }

    public function soughtByUsers()
    {
        return $this->belongsToMany(User::class, 'user_skills')->wherePivot('type', 'seek')->withPivot('proficiency_level')->withTimestamps();
    }

    public function swapsAsOffered()
    {
        return $this->hasMany(Swap::class, 'offered_skill_id');
    }

    public function swapsAsRequested()
    {
        return $this->hasMany(Swap::class, 'requested_skill_id');
    }
}
