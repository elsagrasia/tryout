<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultTryout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tryout_package_id',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tryoutPackage()
    {
        return $this->belongsTo(TryoutPackage::class, 'tryout_package_id');
    }
}
