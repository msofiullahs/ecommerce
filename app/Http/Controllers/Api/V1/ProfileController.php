<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request): UserResource
    {
        return new UserResource($request->user());
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'phone', 'address', 'city', 'province', 'postal_code']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $request->user()->update($data);

        return response()->json([
            'user' => new UserResource($request->user()->fresh()),
            'message' => 'Profile updated successfully',
        ]);
    }
}
