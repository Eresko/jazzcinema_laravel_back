<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\Users\CardService;
use App\Services\Users\UsersServices;
use App\Services\FilmCopy\BookingServices;
use App\Http\Requests\Users\UpdateProfileRequest;
use App\Http\Requests\Users\PushMessageRequest;
use App\Services\Notification\NotificationTelegramService;
use Carbon\Carbon;


class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users/cards",
     *     tags={"USERS"},
     *     summary="Получить карты пользователя",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getCards(Request $request)
    {
        return response()->json(
            app(CardService::class)->getCards(),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/users/profile",
     *     tags={"USERS"},
     *     summary="Получить карты пользователя",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getProfile(Request $request)
    {
        $user = \Auth::user();
        $user->birthday = Carbon::parse( $user->birthday)->format('d.m.Y');
        return response()->json(
            $user,
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/users/reservation",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
     *           )
     *      ),
     *      @OA\Parameter(
     *       name="search",
     *       in="query",
     *       @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */

    public function getReservation(Request $request)
    {
        return response()->json(
            app(BookingServices::class)->getReservationByUser(\Auth::user(),empty($request->pages) ? 1 : (int)$request->pages,$request->search ?? ""),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/users/update",
     *     tags={"USERS"},
     *     summary="Авторизация по дозвону",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"id"},
     *               @OA\Property(
     *                     property="id",
     *                     description="user id",
     *                     type="number",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="fio",
     *                     description="user fio",
     *                     type="string",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="email",
     *                     description="user email",
     *                     type="string",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="datebirth",
     *                     description="user datebirth",
     *                     type="string",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="gender",
     *                     description="user gender",
     *                     type="number",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="password",
     *                     description="user password",
     *                     type="number",
     *                     example="1",
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
    public function updateProfile(UpdateProfileRequest $request)
    {
        $res = app(UsersServices::class)->updateProfile($request->toArray());
        return response()->json(
            $res,
            $res ? 200 : 500
        );

    }
    /**
     * @OA\Post(
     *     path="/api/users/message",
     *     tags={"USERS"},
     *     summary="Авторизация по дозвону",
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *                 type="object",
     *                 required={"email","message","format","photo","theme"},
     *               @OA\Property(
     *                     property="email",
     *                     description="user email",
     *                     type="number",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="message",
     *                     description="user message",
     *                     type="string",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="format",
     *                     description="user format",
     *                     type="string",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="photo",
     *                     description="user photo",
     *                     type="file",
     *                     example="1",
     *                   ),
     *               @OA\Property(
     *                     property="theme",
     *                     description="user theme",
     *                     type="string",
     *                     example="1",
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
    public function pushMessage (PushMessageRequest $request){
        $res = app(NotificationTelegramService::class)->push($request->toDto());
        return response()->json(
            $res,
            $res ? 200 : 500
        );
    }

}