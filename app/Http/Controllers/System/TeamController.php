<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\TeamService;

class TeamController extends ResourceController
{
    public function __construct(private readonly TeamService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\TeamRequest';
    }

    public function moduleName()
    {
        return 'teams';
    }

    public function viewFolder()
    {
        return 'backend.system.team';
    }

}
