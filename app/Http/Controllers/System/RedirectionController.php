<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\RedirectionService;

class RedirectionController extends ResourceController
{
    public function __construct(private readonly RedirectionService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\RedirectionRequest';
    }

    public function moduleName()
    {
        return 'redirections';
    }

    public function viewFolder()
    {
        return 'backend.system.redirection';
    }

}
