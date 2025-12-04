<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get();

        return Inertia::render('Users/Index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return Inertia::render('Users/Create');
    }

    public function edit($id)
    {
        $user = User::select('id', 'name', 'email', 'role')->findOrFail($id);

        return Inertia::render('Users/Edit', [
            'user' => $user,
        ]);
    }
}
