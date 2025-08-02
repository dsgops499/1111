<?php

namespace Modules\GDrive\Http\Controllers;

use Illuminate\Routing\Controller;

class GDriveController extends Controller
{
    public function index() {
        return view('gdrive::index');
    }
}
