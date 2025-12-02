<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\PageService;
use App\Services\TestimonialService;
use App\Services\UserService;

class TestimonialController extends ResourceController
{
    public function __construct(private readonly TestimonialService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\TestimonialRequest';
    }

    public function moduleName()
    {
        return 'testimonials';
    }

    public function viewFolder()
    {
        return 'backend.system.testimonial';
    }

}
