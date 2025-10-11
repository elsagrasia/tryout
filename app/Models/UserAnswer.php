<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'tryout_package_id',
        'question_id',
        'selected_option',
        'is_correct',
    ];

    // Relasi opsional biar mudah ambil data
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryoutPackage()
    {
        return $this->belongsTo(TryoutPackage::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');

    }
}
