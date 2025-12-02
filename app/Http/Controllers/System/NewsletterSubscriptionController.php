<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\NewsletterSubscriptionService;
use App\Services\PageService;
use App\Services\UserService;

class NewsletterSubscriptionController extends ResourceController
{
    public function __construct(private readonly NewsletterSubscriptionService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\NewsletterSubscriptionRequest';
    }

    public function moduleName()
    {
        return 'newsletter-subscriptions';
    }

    public function viewFolder()
    {
        return 'backend.system.newsletter-subscriptions';
    }

}
