<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;



class AuthController extends Controller
{
    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        // Validate incoming data
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                //on definie les conditions pour un bon mot de passe
                Password::min(8)->mixedCase()->numbers()->symbols()
            ]
        ]);
        //We have validated data.
        /** @var User $user*/
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        // Create token for user
        $token = $user->createToken('main')->plainTextToken;

        // Return user and token information
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    /**
     * Login user with email and password
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate incoming data
        $credentials = $request->validate([
            //Email must exist in the users email column
            'email' => 'required|email|string|exists:users,email',
            'password' => 'required',
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        // If credentials are invalid, return error message with status code 422 (Unprocessable Entity)
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'error' => "Informations de connexion incorrects"
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;

        // Return user and token data
        return response([
            'user' => $user,
            'token' => $token
        ]);
    }


   /**
    * Logs out the authenticated user by revoking their access token.
    *
    * @return \Illuminate\Http\Response The response indicating whether the logout was successful.
    */

    public function logout()
    {
    /** @var User $user */
    $user = Auth::user();
    // Revokes the current access token for the authenticated user.
    $user->currentAccessToken()->delete();

    return response([
    'success' => true
    ]);
    }
}
