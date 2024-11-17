<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\API\BaseController;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::role('user')->get();

        return $users->isNotEmpty()
            ? $this->sendResponse($users, __('Users retrieved successfully.'))
            : $this->sendError('Users not found.', 404);
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateUser($request);

            // Handle photo upload
            $photoPath = $this->handlePhotoUpload($request);

            $user = User::create([
                'first_name'    => $validatedData['first_name'],
                'last_name'     => $validatedData['last_name'],
                'email'         => $validatedData['email'],
                'password'      => Hash::make($validatedData['password']),
                'photo'         => $photoPath,
                'mobile'        => $validatedData['mobile'],
                'status'        => $validatedData['status'],
            ]);

            $user->assignRole('User');

            return $this->sendResponse($user, __('User created successfully'), 201);
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 422);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = User::find($id);

        return $user
            ? $this->sendResponse($user, __('User retrieved successfully.'))
            : $this->sendError('User not found.', 404);
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('User not found.', 404);
        }

        try {
            $validatedData = $this->validateUser($request, $id);
            // Handle photo upload if present
            $photoPath = $this->handlePhotoUpload($request, $user->photo);

            $user->first_name = $validatedData['first_name'];
            $user->last_name = $validatedData['last_name'];
            $user->email = $validatedData['email'];

            // Update password only if provided
            if (!empty($validatedData['password'])) {
                $user->password = bcrypt($validatedData['password']);
            }

            $user->photo = $photoPath;
            $user->mobile = $validatedData['mobile'] ?? $user->mobile; // Keep existing value if null
            $user->status = $validatedData['status'];

            $user->save();

            return $this->sendResponse($user, __('User updated successfully.'));
        } catch (ValidationException $e) {
            return $this->sendError($e->errors(), 422);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('User not found.', 404);
        }

        $user->delete();

        return $this->sendResponse([], __('User deleted successfully.'));
    }

    /**
     * Validate user input.
     */
    private function validateUser(Request $request, $userId = null)
    {
        return $request->validate([
            'first_name'    => 'sometimes|string',
            'last_name'     => 'sometimes|string',
            'email'         => 'sometimes|email|unique:users,email,' . $userId,
            'password'      => 'sometimes|string|min:6|confirmed',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mobile'        => 'sometimes|digits:10|regex:/^[6-9]\d{9}$/',
            'status'        => 'sometimes|in:active,inactive',
        ]);
    }

    /**
     * Handle photo upload and return the file path.
     */
    private function handlePhotoUpload(Request $request, $existingPhoto = null)
    {
        if ($request->hasFile('photo')) {
            // Delete existing photo if any
            if ($existingPhoto && file_exists(public_path($existingPhoto))) {
                unlink(public_path($existingPhoto));
            }

            $photo = $request->file('photo');
            $fileName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('user_photos'), $fileName);

            return 'user_photos/' . $fileName;
        }

        return $existingPhoto;  // If no new photo is uploaded, return the existing photo path.
    }
    // retrived user from hoby 
    public function filterByHobby($hobbyId)
    {
        $users = User::whereHas('hobbies', function ($query) use ($hobbyId) {
            $query->where('hobbies.id', $hobbyId);
        })->get();
        if ($users->isEmpty()) {
            return $this->sendError('User not found.', 404);
        }
        return $this->sendResponse($users, 'user retrived successfully');
    }
}
