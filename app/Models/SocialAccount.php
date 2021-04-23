<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $fillable = [
        'provider',
        'provider_user_id',
        'user_id',
    ];
    use HasFactory;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
