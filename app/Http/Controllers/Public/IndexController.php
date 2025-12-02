<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Page;


class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $data = [];
            return view('frontend.index', $data);
        } catch (\Throwable $th) {
        }
    }

    public function pageDirectUrl()
    {
        try {
            $data = [];
            $slug = request()->segment(count(request()->segments()));
            $data['page'] = Page::where('slug', $slug)->where('status', 1)->first();
            if (!$data['page']) {
                return view('frontend.pages.404-not-found');
            }
            return view('frontend.page', $data);
        } catch (\Throwable $th) {
            return view('frontend.pages.404-not-found');
        }
    }
}
