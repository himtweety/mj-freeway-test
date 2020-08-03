<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class HomeController extends BaseController {

    //
    public function show() {
        return view('home.show');
    }

}
