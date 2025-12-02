<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'thumbnail_image',
        'timer',
        'button1_label',
        'button1_link',
        'button1_color',
        'button1_icon',
        'button2_label',
        'button2_link',
        'button2_color',
        'button2_icon',
        'status',
        'long_description',
        'short_description',
        'position',
    ];
}
