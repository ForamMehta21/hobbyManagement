<?php 
namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends BaseController
{
    //login method
    public function login(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if user exists with given email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            // Return error if credentials are incorrect
            return $this->sendError('These credentials do not match our records',401);
        }
        if (!$user->status) {
            // Return error if account is inactive
            return $this->sendError('Your account is inactive. Please contact the administrator.',403);
        }

        // Generate a new token for the user
        $user->token = $user->createToken('API Token')->plainTextToken;

        // Return response with token
        return $this->sendResponse($user, __('Login Successfully'));
    }
}
