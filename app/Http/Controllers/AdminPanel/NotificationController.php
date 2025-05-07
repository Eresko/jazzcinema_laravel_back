<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Notification\NotificationTelegramService;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\CreateNotificationRequest;

class NotificationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/notification",
     *     tags={"Админ панель"},
     *     summary="Получение списка id оповещения",
     *     @OA\Parameter(
     *        name="page",
     *        in="query",
     *        @OA\Schema(
     *                type="integer"
     *            )
     *       ),
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
            app(NotificationTelegramService::class)->list(empty($request->pages) ? 1 : (int)$request->pages),
            200
        );

    }

    /**
     * @OA\Delete(
     *     path="/api/admin-panel/notification/{id}",
     *     tags={"Админ панель"},
     *     summary="Удаление записи из списка оповещения по id",
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
            app(NotificationTelegramService::class)->delete((int)$id),
            200
        );

    }
    /**
     * @OA\Post(
     *     path="/api/admin-panel/notification",
     *      security={{"bearerAuth":{}}},
     *     tags={"Админ панель"},
     *     summary="Создание банера",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"telegram"},
     *                 @OA\Property(
     *                     property="telegram",
     *                     description="telegram",
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
    public function create(CreateNotificationRequest $request)
    {

        return app(NotificationTelegramService::class)->create($request->telegram);

    }
}
