<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\ProjectService;

class ProjectController extends ResourceController
{
    public function __construct(private readonly ProjectService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\ProjectRequest';
    }

    public function updateValidationRequest()
    {
        return 'App\Http\Requests\System\ProjectRequest';
    }

    public function moduleName()
    {
        return 'projects';
    }

    public function viewFolder()
    {
        return 'backend.system.project';
    }
}

