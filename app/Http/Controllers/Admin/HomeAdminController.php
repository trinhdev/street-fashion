<?php

namespace App\Http\Controllers\Admin;

class HomeAdminController extends BaseController
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     * 
     */
    public function __construct()
    {
        parent::__construct();
        $this->title = 'StreetFashion';
    }
    public function index()
    {
        return view('admin.homev2');
    }


}
