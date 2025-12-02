<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\ResourceController;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends ResourceController
{
    public function __construct(private readonly MenuService $menuService)
    {
        parent::__construct($menuService);
    }

    public function storeValidationRequest()
    {
        return 'App\Http\Requests\System\MenuRequest';
    }

    public function moduleName()
    {
        return 'menus';
    }

    public function viewFolder()
    {
        return 'backend.system.menu';
    }

    public function index(Request $request, $id = '')
    {
        $data = $this->service->indexPageData($request);
        if (!$data['thisData']) {
            return redirect()->back()->withErrors(['error' => 'Save config first.']);
        }
        $data['breadcrumbs'] = $this->breadcrumbForIndex();
        $this->setModuleId($id);
        return $this->renderView('index', $data);
    }

}
