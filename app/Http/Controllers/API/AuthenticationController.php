<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticationController extends Controller
{
    //
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'username'    => 'required|string|max:255',
                'password' => 'required|string|max:255',
            ],[],[
                'username'  => 'nom d\'utilisateur',
                'password'  => 'mot de passe',
            ]);
            $user = User::where('name',$validated['username'])->first();
            if (!$user || !Auth::attempt(['email'=>$user->email,'password'=>$validated['password']])) {
                return response()->json([
                    'response_code' => 401,
                    'status'        => 'error',
                    'message'       => 'Unauthorized',
                ], 401);
            }
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'status_code'   => 200,
                'status'        => 'success',
                'message'       => 'Vous êtes connecté avec succès',
                'token'         => $token,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'response_code' => 422,
                'status'        => 'error',
                'message'       => 'Validation failed',
                'errors'        => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login Error: ' . $e->getMessage());

            return response()->json([
                'response_code' => 500,
                'status'        => 'error',
                'message'       => 'Login failed',
            ], 500);
        }
    }
}
