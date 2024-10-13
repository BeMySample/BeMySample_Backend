<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        return UserController::all();
    }

    public function store(Request $request) {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'required',
        ]);
        
        return UserController::create($request->all());
    }

    public function show($id) {
        return UserController::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $user = UserController::findOrFail($id);
        $user->update($request->all());
        return $user;
    }

    public function destroy($id) {
        UserController::destroy($id);
        return response(null, 204);
    }
}
