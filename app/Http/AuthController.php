<?php

namespace App\Http;

use App\Models\User;
use App\Validators\LoginValidator;
use App\Validators\RegisterValidator;
use Core\Contracts\Http\Controller;
use Core\Contracts\Http\Request;
use Core\Contracts\Http\Response;

class AuthController extends Controller
{
    public function loginForm(): Response
    {
        return response()->withView('auth/login', ['error' => null, 'old_inputs' => []]);
    }

    public function login(Request $request): Response
    {
        $validator = LoginValidator::make();
        $validated = $validator->validate($request->body());
        if ($validated && $this->tryLogin($validated)) {
            return response()->redirect('profile');
        }

        return response()->withView('auth/login', [
             'error'      => 'Email or Password are incorrect',
             'old_inputs' => $validator->data(),
        ], 401);
    }

    public function registerForm(): Response
    {
        return response()->withView('auth/register', ['errors' => [], 'old_inputs' => []]);
    }

    public function register(Request $request): Response
    {
        $validator = RegisterValidator::make();
        $validated = $validator->validate($request->body());
        if ($validated) {
            $validated['password'] = password_hash($validated['password'], PASSWORD_DEFAULT);
        }
        if ($validated && $user = User::create($validated)) {
            auth()->login($user);
            return response()->redirect('/');
        }

        return response()->withView('auth/register', [
             'errors'     => $validator->errors(),
             'old_inputs' => $validator->data(),
        ], 401);
    }

    public function logout(): Response
    {
        auth()->logout();
        return response()->redirect('/');
    }

    private function tryLogin(array $credentials): bool
    {
        $user = User::query()->where('email', $credentials['email'])->first();
        if (!$user || !password_verify($credentials['password'], $user->password)) {
            return false;
        }
        return auth()->login($user);
    }
}
