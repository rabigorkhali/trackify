<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\PartnerService;

class PartnerController extends ResourceController
{
    public function __construct(private readonly PartnerService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\PartnerRequest';
    }

    public function moduleName()
    {
        return 'partners';
    }

    public function viewFolder()
    {
        return 'backend.system.partner';
    }

}
