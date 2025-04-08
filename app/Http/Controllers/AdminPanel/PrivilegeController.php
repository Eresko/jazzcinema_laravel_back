<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Users\UsersServices;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\PrivilegeUpdateRequest;

class PrivilegeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/privilege/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение пользователя по id",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getPrivilege(Request $request, $id)
    {
        return response()->json(
            app(UsersServices::class)->getPrivilege((int)$id),
            200
        );
    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/privilege/{id}",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление зала",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"specifiedReservationLimit","specifiedSalesLimit","salesAllowed"},
     *                 @OA\Property(
     *                     property="specifiedReservationLimit",
     *                     description="Лимит на бронирование",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                      property="specifiedSalesLimit",
     *                      description="Лимит на продажу",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                       property="salesAllowed",
     *                       description="разрешение на продажу",
     *                       type="string",
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
     *
     */
    public function update(PrivilegeUpdateRequest $request, $id)
    {

        return app(UsersServices::class)->updatePrivilege((int)$id, $request->toDto());

    }






}
