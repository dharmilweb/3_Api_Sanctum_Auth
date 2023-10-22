<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;

class AuthController extends Controller
{
    //
        /**
    * @OA\Post(
    *    path="/api/register",
    *    tags={"Authentication"},
    *    summary="Register Api",
        *    operationId="User Register",
        *    @OA\Parameter(
        *        name="name",
        *        in="query",
        *        required=true,
        *        @OA\Schema(
        *            type="string",
        *            example="UserSample1"
        *        )
        *    ),
        *    @OA\Parameter(
        *        name="email",
        *        in="query",
        *        required=true,
        *        @OA\Schema(
        *            type="string",
        *            example="UserSample1@gmail.com"
        *        )
        *    ),
        *    @OA\Parameter(
        *        name="password",
        *        in="query",
        *        required=true,
        *        @OA\Schema(
        *            type="string",
        *            example="UserSample1"
        *        )
        *    ),
        *    @OA\Response(
        *        response=200,
        *        description="Success",
        *        @OA\MediaType(
        *            mediaType="application/json",
        *        )
        *    ),
        *    @OA\Response(
        *        response=401,
        *        description="Unauthorized"
        *    ),
        *    @OA\Response(
        *        response=400,
        *        description="Invalid request"
        *    ),
        *    @OA\Response(
        *        response=403,
        *        description="Unauthorized Access"
        *    ),
        *    @OA\Response(
        *        response=404,
        *        description="not found"
        *    ),
        *)
    */
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'token' => $user->createToken("API TOKEN")->plainTextToken
        ], 200);
    }

    /**
    * @OA\Post(
    *    path="/api/login",
    *    tags={"Authentication"},
    *    summary="Login Api",
        *    operationId="User Login",
        *    @OA\Parameter(
        *        name="email",
        *        in="query",
        *        required=true,
        *        @OA\Schema(
        *            type="string",
        *            example="UserSample1@gmail.com"
        *        )
        *    ),
        *    @OA\Parameter(
        *        name="password",
        *        in="query",
        *        required=true,
        *        @OA\Schema(
        *            type="string",
        *            example="UserSample1"
        *        )
        *    ),
        *    @OA\Response(
        *        response=200,
        *        description="Success",
        *        @OA\MediaType(
        *            mediaType="application/json",
        *        )
        *    ),
        *    @OA\Response(
        *        response=401,
        *        description="Unauthorized"
        *    ),
        *    @OA\Response(
        *        response=400,
        *        description="Invalid request"
        *    ),
        *    @OA\Response(
        *        response=403,
        *        description="Unauthorized Access"
        *    ),
        *    @OA\Response(
        *        response=404,
        *        description="not found"
        *    ),
        *)
    */
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'User Login Successfully',
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ], 200);
    }

    /**
    * @OA\Post(
    *    path="/api/auth/me",
    *    tags={"Authentication"},
    *    summary="Me Api",
        *    operationId="Get Login User Details",
        *    @OA\Response(
        *        response=200,
        *        description="Success",
        *        @OA\MediaType(
        *            mediaType="application/json",
        *        )
        *    ),
        *    @OA\Response(
        *        response=401,
        *        description="Unauthorized"
        *    ),
        *    @OA\Response(
        *        response=400,
        *        description="Invalid request"
        *    ),
        *    @OA\Response(
        *        response=403,
        *        description="Unauthorized Access"
        *    ),
        *    @OA\Response(
        *        response=404,
        *        description="not found"
        *    ),
        *   security={{ "apiAuth": {} }}
        *)
    */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
    * @OA\Post(
    *    path="/api/auth/logout",
    *    tags={"Authentication"},
    *    summary="logout Api",
        *    operationId="",
        *    @OA\Response(
        *        response=200,
        *        description="Success",
        *        @OA\MediaType(
        *            mediaType="application/json",
        *        )
        *    ),
        *    @OA\Response(
        *        response=401,
        *        description="Unauthorized"
        *    ),
        *    @OA\Response(
        *        response=400,
        *        description="Invalid request"
        *    ),
        *    @OA\Response(
        *        response=403,
        *        description="Unauthorized Access"
        *    ),
        *    @OA\Response(
        *        response=404,
        *        description="not found"
        *    ),
        *   security={{ "apiAuth": {} }}
        *)
    */
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
