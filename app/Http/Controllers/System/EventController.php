<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\EventService;


class EventController extends ResourceController
{
    public function __construct(private readonly EventService $thisService)
    {
        parent::__construct($thisService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\EventRequest';
    }

    public function moduleName()
    {
        return 'events';
    }

    public function viewFolder()
    {
        return 'backend.system.event';
    }

    public function deleteGallery($id)
    {
        try {
            $this->service->deleteGallery($id);
            return redirect()->back()->withErrors(['success' => 'Gallery image successfully deleted.']);
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
        }
    }

}
