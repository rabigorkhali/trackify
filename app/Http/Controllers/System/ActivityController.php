<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\ActivityService;
use App\Services\PostService;

class ActivityController extends ResourceController
{
    public function __construct(private readonly ActivityService $thisService)
    {
        parent::__construct($thisService);
    }


    public function moduleName()
    {
        return 'activities';
    }

    public function viewFolder()
    {
        return 'backend.system.activity';
    }

}
