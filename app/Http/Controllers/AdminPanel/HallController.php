<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Halls\HallServices;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\UpdateHallRequest;

class HallController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/halls",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
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
        return response()->json(
            app(HallServices::class)->list(),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/halls/{id}",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление зала",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"is_display_schedule"},
     *                 @OA\Property(
     *                     property="is_display_schedule",
     *                     description="флаг отображения рассписания на сайте",
     *                     type="string",
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
     *
     */
    public function update(UpdateHallRequest $request, $id)
    {

        return app(HallServices::class)->update((int)$id, $request->is_display_schedule == 'true');

    }
}
