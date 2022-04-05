<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request, User $user)
    {
        $user->name = $request->name ?? $request->email;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return $user->generateAndSaveApiAuthToken()->makeVisible('api_token');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('web')->attempt($credentials)) {
            $user = Auth::guard('web')
                        ->user()
                        ->generateAndSaveApiAuthToken()->makeVisible('api_token');

            return $user;
        }

        return response()->json(['message' => 'Authentication error'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['message' => 'Logged out'], 200);
    }
}
