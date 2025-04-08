<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\Response;
use App\Services\Users\UsersServices;

class UserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/user/login",
     *     tags={"Auth"},
     *     summary="Auth manager",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"login", "password"},
     *               @OA\Property(
     *                     property="login",
     *                     description="логин пользователя",
     *                     type="string",
     *                     example="123123",
     *                   ),
     *                @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="123123",
     *                 ),
     *          )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {

        return app(UsersServices::class)->login($request->login, $request->password);
    }


}
