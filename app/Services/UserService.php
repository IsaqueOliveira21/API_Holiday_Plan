<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use PHPUnit\Util\Exception;
use Symfony\Component\HttpFoundation\Response;

class UserService {

    public function login(array $input) {
        if(!auth()->attempt($input)) {
            return throw new HttpResponseException(response()->json(['invalidEmailOrPassword' => 'Invalid e-mail or password'], 401));
        } else {
            $genToken = auth()->user()->createToken('accessToken');
            $token = explode('|', $genToken->plainTextToken); // Expire in 24h
            return response()->json(['token' => $token[1]], 200);
        }
    }

    public function logout() {
        auth()->user()->currentAccessToken()->delete();
        return response()->json(['logged out'], 200);
    }

    public function create($input) {
        try {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => bcrypt($input['password'])
            ]);
            return $user;
        } catch(HttpResponseException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update($input) {
        try {
            $user = auth()->user();
            $user->fill([
                'name' => $input['name'] ?? $user->name,
                'email' => $input['email'] ?? $user->email,
                'password' => bcrypt($input['password'])
            ]);
            $user->save();
            return $user;
        } catch(HttpResponseException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
