<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function tryoutPackage(){
    return $this->belongsTo(TryoutPackage::class, 'tryout_id' ,'id');

    }

    

}
