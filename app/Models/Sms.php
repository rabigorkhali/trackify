<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sms extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'sender',
        'receiver',
        'message',
        'status',
        'meta',
    ];
    protected $casts = [
        'receiver' => 'array', // if you plan to store JSON array of emails
    ];

}
