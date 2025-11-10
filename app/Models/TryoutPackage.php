<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutPackage extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Relasi ke kategori utama (optional)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function categories(){
        return $this->belongsToMany(Category::class, 'category_tryout_package', );
    }
    
    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'package_question',     // pivot
            'tryout_package_id',    // FK ke tryout_packages
            'question_id'           // FK ke questions
        );
    }

    public function attempts() {
        return $this->hasMany(Attempt::class);
    }
    
}
