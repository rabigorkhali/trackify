<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Models\User;
use App\Services\EmailService;
use App\Services\PageService;
use App\Services\UserService;

class EmailController extends ResourceController
{
    public function __construct(private readonly EmailService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\EmailRequest';
    }

    public function moduleName()
    {
        return 'emails';
    }

    public function viewFolder()
    {
        return 'backend.system.emails';
    }

    public function create()
    {
        $request = $this->defaultRequest();
        $request = app()->make($request);
        $data = $this->service->createPageData($request);
        $this->setModuleId($request->id);
        $data['breadcrumbs'] = $this->breadcrumbForForm('Create');
        $data['users']=User::get();

        return $this->renderView('create', $data);
    }
}
