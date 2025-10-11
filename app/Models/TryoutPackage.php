<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutPackage extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_tryout_package');
    }
    
    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'question_tryout', 'tryout_id', 'question_id');
    }

    public function attempts() {
        return $this->hasMany(Attempt::class);
    }
}
