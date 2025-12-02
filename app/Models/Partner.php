<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'description',
        'contact',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'linkedin_url',
        'twitter_url',
        'status',
        'image',
        'position',
    ];

}
