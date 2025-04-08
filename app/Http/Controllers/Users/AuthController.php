<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\Users\UsersServices;
use App\Http\Requests\Users\AuthPhoneRequest;
use App\Http\Requests\Users\CheckCodeRequest;
use App\Http\Requests\Users\AuthLoginRequest;
use App\Http\Requests\Users\AuthPasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Users\UserRepository;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/users/auth/phone",
     *     tags={"USERS"},
     *     summary="Авторизация по дозвону",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"phone"},
     *               @OA\Property(
     *                     property="phone",
     *                     description="телефон пользователя",
     *                     type="string",
     *                     example="89999997777",
     *                   ),
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
    public function authPhone(AuthPhoneRequest $request)
    {
        $res = app(UsersServices::class)->getAuthPhone($request->phone);
        return response()->json(
            $res,
            $res ? 200 : 500
        );

    }

    /**
     * @OA\Post(
     *     path="/api/users/auth/check-sms-code",
     *     tags={"USERS"},
     *     summary="Авторизация по коду из sms или дозвона",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"phone"},
     *               @OA\Property(
     *                     property="phone",
     *                     description="телефон пользователя",
     *                     type="string",
     *                     example="89999997777",
     *                   ),
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
    public function checkSmsCode(CheckCodeRequest $request)
    {
        $res = app(UsersServices::class)->checkCode($request->code);
        return response()->json(
            $res,
            $res ? 200 : 500
        );

    }

    /**
     * @OA\Post(
     *     path="/api/users/auth",
     *     tags={"USERS"},
     *     summary="Авторизация по коду из sms или дозвона",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"login","password"},
     *               @OA\Property(
     *                     property="login",
     *                     description="Логин пользователя",
     *                     type="string",
     *                     example="test",
     *                   ),
     *               @OA\Property(
     *                      property="password",
     *                      description="пароль пользователя",
     *                      type="string",
     *                      example="qwerty",
     *                    ),
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
    public function login(AuthLoginRequest $request)
    {
        $res = app(UsersServices::class)->loginGuest($request->login, $request->passwrod);
        return response()->json(
            $res,
            $res ? 200 : 500
        );

    }


    /**
     * @OA\Get(
     *     path="/api/users/auth/check-token",
     *     security={{"bearerAuth":{}}},
     *     tags={"USERS"},
     *     summary="Проверка токена",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function checkToken(Request $request)
    {
        $user =  \Auth::user();
        return response()->json(
            isset($user),
            200
        );

    }


    /**
     * @OA\Post(
     *     path="/api/users/auth/password",
     *     tags={"USERS"},
     *     summary="Авторизация по паролю",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"phone","password"},
     *               @OA\Property(
     *                     property="phone",
     *                     description="телефон пользователя",
     *                     type="string",
     *                     example="89999997777",
     *                   ),
     *                @OA\Property(
     *                      property="password",
     *                      description="пароль пользователя",
     *                      type="string",
     *                      example="89999997777",
     *                ),
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
    public function authPassword(AuthPasswordRequest $request)
    {
        $res = app(UsersServices::class)->loginByPhone($request->login, $request->password);
        return response()->json(
            $res,
            $res ? 200 : 500
        );

    }


    /**
     * @OA\Get(
     *     path="/api/users/auth/test",
     *     tags={"USERS"},
     *     summary="Проверка токена",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function test(Request $request)
    {
        $user = app(UserRepository::class)->getUserByPhone('79128060555');
        $token = auth()->claims(['foo' => 'bar'])->login($user);
        $payload = auth()->payload();
        $payload->get('sub'); // = 123
        $payload['jti']; // = 'asfe4fq434asdf'
        $payload('exp'); // = 123456


        return response()->json(
            [
                $payload->toArray(),
                $token
            ],
            200
        );

    }

}
