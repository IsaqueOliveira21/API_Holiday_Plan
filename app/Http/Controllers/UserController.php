<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function login(Request $request) {
        $input = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        return $this->service->login($input);
    }

    public function logout() {
        return $this->service->logout();
    }

    public function create(Request $request) {
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:20'
        ]);
        $user = $this->service->create($input);
        return new UserResource($user);
    }

    public function update(Request $request) {
        $input = $request->validate([
            'name' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string|min:8|max:20'
        ]);
        $user = $this->service->update($input);
        return new UserResource($user);
    }
}
