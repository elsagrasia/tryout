<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tryoutPackages()
    {
        return $this->belongsToMany(TryoutPackage::class, 'category_tryout_package')->withTimestamps();
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }    
}
