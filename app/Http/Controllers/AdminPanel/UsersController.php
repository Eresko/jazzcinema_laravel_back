<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Users\UsersServices;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\UpdateUserRequest;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/users",
     *     tags={"Админ панель"},
     *     summary="Получение списка пользователей",
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
    public function get(Request $request)
    {
        return response()->json(
            app(UsersServices::class)->list(empty($request->page) ? 1 : (int)$request->page, strlen($request->search) < 2 ? null : $request->search),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/admin-panel/users/{id}",
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
    public function getUser(Request $request, $id)
    {
        return response()->json(
            app(UsersServices::class)->getById((int)$id),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/users/{id}",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление зала",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"email","gender","phone","birthday","fio"},
     *                 @OA\Property(
     *                     property="email",
     *                     description="email пользователя",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                      property="gender",
     *                      description="пол пользователя",
     *                      type="number",
     *                  ),
     *                 @OA\Property(
     *                      property="birthday",
     *                      description="Дата рождения пользователя",
     *                      type="string",
     *                  ),
     *                 @OA\Property(
     *                       property="phone",
     *                       description="Телефон  пользователя",
     *                       type="string",
     *                   ),
     *                 @OA\Property(
     *                        property="fio",
     *                        description="ФИО  пользователя",
     *                        type="string",
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
     *
     */

    public function updateUser(UpdateUserRequest $request, $id)
    {
        return response()->json(
            app(UsersServices::class)->getById((int)$id),
            200
        );

    }


}
