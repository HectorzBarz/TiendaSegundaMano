<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $users = User::latest()->get();

        return response()->json($users);
    }

    public function show(Request $request, $id)
    {
        // Seguridad admin
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(UpdateProfileRequest $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $user = User::findOrFail($id);

        $validatedData = $request->validated();

        // Imagen
        if ($request->hasFile('profile_image')) {

            if (
                $user->profile_image &&
                !str_contains($user->profile_image, 'default_image.jpg')
            ) {

                $oldPath = str_replace('/storage/', '', $user->profile_image);

                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('profile_image')
                ->store('profile-images', 'public');

            $validatedData['profile_image'] = '/storage/' . $path;
        }

        // Password
        if (isset($validatedData['password'])) {
            $validatedData['password'] =
                Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Usuario actualizado correctamente',
            'user' => $user->fresh()
        ]);
    }
}