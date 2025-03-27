<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Users\CardService;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\FilmCopyUpdateRequest;

class CardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/cards/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение списка карт пользователя",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getCards(Request $request, $id)
    {
        return response()->json(
            app(CardService::class)->getCardsByUserId((int)$id),
            200
        );

    }

}
