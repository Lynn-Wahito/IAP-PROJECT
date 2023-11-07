<?php

namespace App\Http\Controllers;


use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class PostController extends Controller
{
    public function index()
    {
        return view("user/index");
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        // Add more validation rules as needed
    ]);

    // Your code to save the data if validation passes

    return redirect()->back()->with('success', 'Data saved successfully');
}

//to be in RegisterController.php file.
protected function create(array $data)
{
    // Create a new user with the provided data
    $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    // Retrieve the role value from the form and assign it to the user
    $role = $data['role'];

    // Assign the role to the user
    $user->assignRole($role);

    return $user;
}


public function assignRole($role)
{
    $role = Role::where('name', $role)->first();

    if ($role) {
        $this->roles()->attach($role);
    }
}

}
