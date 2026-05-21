<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Extraemos los datos ya validados por el Request
        $validatedData = $request->validated();

        // Convertimos la fecha recogida "DD/MM/YYYY" a "YYYY-MM-DD"
        $formattedBirthDate = Carbon::createFromFormat('d/m/Y', $validatedData['birth_date'])->format('Y-m-d');

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'phone' => $validatedData['phone'],       // Forzado explícito
            'birth_date' => $formattedBirthDate,           // Forzado explícito
            'password' => Hash::make($validatedData['password']),
            'city' => $validatedData['city'],
            'postal_code' => $validatedData['postal_code'],
            'shipping_address' => $validatedData['shipping_address'],
            'billing_address' => $validatedData['billing_address'] ?? $validatedData['shipping_address'],
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado con éxito',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}