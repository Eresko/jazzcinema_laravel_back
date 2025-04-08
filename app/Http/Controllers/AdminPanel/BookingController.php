<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\FilmCopy\BookingServices;
use Illuminate\Http\Request;
use App\Repositories\Users\UserRepository;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/booking",
     *     tags={"Админ панель"},
     *     summary="Получение списка бронирования",
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
     *      @OA\Parameter(
     *       name="start",
     *       in="query",
     *       @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *      @OA\Parameter(
     *       name="end",
     *       in="query",
     *       @OA\Schema(
     *               type="string"
     *           )
     *      ),
     *     @OA\Parameter(
     *       name="user_id",
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
    public function get(Request $request)
    {
        $user = empty($request->user_id) ? null : app(UserRepository::class)->getById((int)$request->user_id);
        return response()->json(
            empty($user) ?
            app(BookingServices::class)->getReservation(
                empty($request->page) ? 1 : (int)$request->page,
                strlen($request->search) < 2 ? null : $request->search,
                $request->start,
                $request->end,
            ) :
            app(BookingServices::class)->getReservationByUser($user, empty($request->page) ? 1 : (int)$request->page, strlen($request->search) < 2 ? null : $request->search),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/admin-panel/booking/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение бронирование по id",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getUser(Request $request, $id)
    {
        return response()->json(
            app(BookingServices::class)->getById((int)$id),
            200
        );

    }

}
