<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\TicketLabelService;

class TicketLabelController extends ResourceController
{
    public function __construct(private readonly TicketLabelService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\TicketLabelRequest';
    }

    public function updateValidationRequest()
    {
        return 'App\Http\Requests\System\TicketLabelRequest';
    }

    public function moduleName()
    {
        return 'ticket-labels';
    }

    public function viewFolder()
    {
        return 'backend.system.ticket-label';
    }
}

