<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index() {
        return view("host.index");
    }
    public function customerIndex() {
        return view("customer.customerIndex");
    }
}

