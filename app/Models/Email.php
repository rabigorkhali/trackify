<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Email extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_email',
        'to_email',
        'subject',
        'body',
        'status',
    ];
    protected $casts = [
        'to_email' => 'array', // if you plan to store JSON array of emails
    ];

}
