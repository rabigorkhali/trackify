<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Models\User;
use App\Services\SmsService;

class SmsController extends ResourceController
{
    public function __construct(private readonly SmsService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\SmsRequest';
    }

    public function moduleName()
    {
        return 'sms';
    }

    public function viewFolder()
    {
        return 'backend.system.sms';
    }
    
}
