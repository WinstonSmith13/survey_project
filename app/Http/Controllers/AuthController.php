<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Permet d'ajouter des regles pour la validations de l'inscription.
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string|unique:users,email',
            'password' => [
                'required',
                'confirmed',
                //on definie les conditions pour un bon mot de passe
                Password::min(8)->mixedCase()->numbers()->symbols()]
        ]);

        /** @var User */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //Création du token
        $token = $user->createToken('main')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);

    }

    public function login(Request $request){

        $credentials = $request->validate([
            'email' => 'required|email|string|exists:users,email',
            'password' => [
                'required',
            ],
            'remember' => 'boolean'
        ]);
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);

        //Si les valeurs ne correspondent pas alors on déclare un message. Avec le fameux statut 422 (qui indique que la validation n'est pas bonne.
        if(!Auth::attempt($credentials, $remember)){
            return response ([
                'error' => "les informations d'identification fournies ne sont pas valides"
            ], 422);
        }
        $user = Auth::user();
        $token = $user->createToken('main')->plainTextToken;
        return response([
           'user'=>$user,
           'token'=>$token
        ]);

    }

}

