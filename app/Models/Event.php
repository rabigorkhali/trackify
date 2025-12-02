<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'slug',
        'video_url',
        'short_description',
        'long_description',
        'thumbnail_image',
        'status',
    ];

    public function galleries()
    {
        return $this->hasMany(EventImage::class);
    }

}
