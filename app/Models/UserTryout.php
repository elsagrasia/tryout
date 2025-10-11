<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTryout extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id',
    'tryout_package_id',
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
