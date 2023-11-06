<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function showRegistrationForm()
{
    $roles = Role::all();
    return view('auth.register', compact('roles'));
}

}
