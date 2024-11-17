<?php

namespace App\Http\Controllers\API;

use App\Models\Hobby;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;

class HobbyController extends BaseController
{
    //hooby list
    public function index()
    {
        $hobbies = Hobby::all();
        return $this->sendResponse($hobbies, __('Hobbies retrived Successfully.'));
    }

    // Update hobbies for logged-in user
    public function updateHobbies(Request $request)
    {
        // Validate the hobbies input
        $validator = Validator::make($request->all(), [
            'hobbies' => 'required|string', 
        ]);
    
        // If validation fails, return a custom error response
        if ($validator->fails()) {
            return $this->sendError($validator->errors(), 400);
        }
    
        // Convert the comma-separated string into an array of integers
        $hobbies = array_map('intval', explode(',', $request->input('hobbies')));

        // Validate that each hobby ID exists in the hobbies table
        $validator = Validator::make(['hobbies' => $hobbies], [
            'hobbies.*' => 'exists:hobbies,id',  // Ensure each hobby ID exists in the hobbies table
        ]);
    
        // If validation fails for hobby IDs, return a custom error response
        if ($validator->fails()) {
            return $this->sendError('One or more hobby IDs are invalid.', 400);
        }
    
        // Get the authenticated user
        $user = $request->user();
    
        // Sync the hobbies
        $user->hobbies()->sync($hobbies);
    
        // Return a success message
        return $this->sendResponse([], __('Hobbies updated successfully'));
    }
    
}
