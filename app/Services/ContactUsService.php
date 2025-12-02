<?php

namespace App\Services;

use App\Models\ContactUs;

class ContactUsService extends Service
{
    public function __construct(ContactUs $model)
    {
        parent::__construct($model);
    }
}
