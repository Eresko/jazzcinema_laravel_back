<?php

namespace App\Http\Controllers\FilmCopy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\FilmCopy\ScheduleServices;
use Illuminate\Http\Request;
class ScheduleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/film-copy/movie-schedule",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
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
    public function getScheduleByGroupDate(Request $request)
    {
        return response()->json(
            ['shedule_films' => array_values(app(ScheduleServices::class)->getScheduleByGroupDate()->toArray())],
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/movie-schedule/{performance}",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
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

    public function getScheduleByPerformance(Request $request,$performance)
    {
        return response()->json(
            app(ScheduleServices::class)->getScheduleByPerformance($performance),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/status/{performance}",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
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

    public function getStatusByPerformance(Request $request,$performance)
    {
        return response()->json(
            app(ScheduleServices::class)->getStatusByPerformance($performance),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/status-auth/{performance}",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
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

    public function getStatusAuthByPerformance(Request $request,$performance)
    {

        //$user = \Auth::user();
        return response()->json(
            app(ScheduleServices::class)->getStatusByPerformance($performance),
            200
        );

    }

}