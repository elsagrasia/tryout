<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function package() {
        return $this->belongsTo(TryoutPackage::class, 'tryout_package_id');
    }
    public function answers() {
        return $this->hasMany(Answer::class);
    }    
}
