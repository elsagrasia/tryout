<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TryoutHistory extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'tryout_package_id',
        'total_questions',
        'correct_count',
        'wrong_count',
        'unanswered_count',
        'score',
        'finished_at',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tryout_package
    public function tryoutPackage()
    {
        return $this->belongsTo(TryoutPackage::class, 'tryout_package_id');
    }
}
