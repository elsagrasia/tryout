<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $guarded = [];
        public function users()
    {
        return $this->belongsToMany(User::class, 'user_points')->withTimestamps();
    }
        public function userPoints()
        {
            return $this->hasMany(UserPoint::class, 'point_rule_id');
        }


}
