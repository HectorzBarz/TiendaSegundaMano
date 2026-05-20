<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Devuelve la información del usuario logueado actualmente
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    // Actualiza la información del usuario y su imagen
    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();
        $validatedData = $request->validated();

        // 1. Manejo de la subida de imagen
        if ($request->hasFile('profile_image')) {

            // Si el usuario ya tenía una imagen personalizada, la borramos del servidor para ahorrar espacio
            if ($user->profile_image && !str_contains($user->profile_image, 'default_image.jpg')) {
                // Quitamos el '/storage/' de la ruta para obtener el path real dentro del disco 'public'
                $oldPath = str_replace('/storage/', '', $user->profile_image);
                Storage::disk('public')->delete($oldPath);
            }

            // Guardamos la nueva imagen
            $path = $request->file('profile_image')->store('profile-images', 'public');
            $validatedData['profile_image'] = '/storage/' . $path;
        }

        // 2. Manejo de la contraseña
        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // Evitamos sobreescribir la contraseña con null si no se envió
            unset($validatedData['password']);
        }

        // 3. Actualizamos el modelo
        $user->update($validatedData);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->fresh() // Retornamos el usuario con los datos actualizados
        ]);
    }
}