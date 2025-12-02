<?php

namespace App\Services;

use App\Models\Testimonial;

class TestimonialService extends Service
{
    public function __construct(Testimonial $model)
    {
        parent::__construct($model);
    }
}
