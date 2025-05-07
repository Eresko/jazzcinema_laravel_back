<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\FilmCopy\ScheduleServices;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\GetScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/schedule",
     *     tags={"Админ панель"},
     *     summary="Получение списка расписания",
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
    public function get(GetScheduleRequest $request)
    {

        return response()->json(
            app(ScheduleServices::class)->getScheduleByFilter($request->toDto()),
            200
        );

    }

    /**
     * @OA\Delete(
     *     path="/api/admin-panel/schedule/{id}",
     *     tags={"Админ панель"},
     *     summary="Удаление сеанса по id",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function delete(Request $request, $id)
    {
        return response()->json(
            app(ScheduleServices::class)->delete((int)$id),
            200
        );

    }

}
