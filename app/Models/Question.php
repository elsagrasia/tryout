<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function packages()
    {
        return $this->belongsToMany(TryoutPackage::class, 'package_question', 'question_id', 'tryout_package_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    
    public function answers() {
        return $this->hasMany(Answer::class);
    }

  
}
