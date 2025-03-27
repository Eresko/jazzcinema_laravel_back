<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Notification\NotificationTelegramService;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\UpdateHallRequest;

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
}
