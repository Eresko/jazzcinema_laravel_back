<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Services\Users\UsersServices;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Users\StaffService;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\UpdateStaffRequest;
use App\Http\Requests\AdminPanel\CreateStaffRequest;

class StaffController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/staff",
     *     tags={"Админ панель"},
     *     summary="Получение списка персонала",
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
            app(StaffService::class)->list(empty($request->page) ? 1 : (int)$request->page, strlen($request->search) < 2 ? null : $request->search),
            200
        );

    }
    /**
     * @OA\Get(
     *     path="/api/admin-panel/staff/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение персонала по id",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getStaff(Request $request, $id)
    {
        return response()->json(
            app(UsersServices::class)->getById((int)$id),
            200
        );

    }

    /**
     * @OA\Delete(
     *     path="/api/admin-panel/staff/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение персонала по id",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function deleteStaff(Request $request, $id)
    {
        return response()->json(
            app(UsersServices::class)->deleteById((int)$id),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/staff/{id}",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление персонала",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"email","password","name"},
     *                 @OA\Property(
     *                     property="email",
     *                     description="email пользователя",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                       property="password",
     *                       description="Пароль  пользователя",
     *                       type="string",
     *                   ),
     *                 @OA\Property(
     *                        property="name",
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

    public function updateStaff(UpdateStaffRequest $request, $id)
    {
        return response()->json(
            app(StaffService::class)->update((int)$id,$request->toDto()),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/staff",
     *      security={{"bearerAuth":{}}},
     *     summary="Создание персонала",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"email","password","name"},
     *                 @OA\Property(
     *                     property="email",
     *                     description="email пользователя",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                       property="password",
     *                       description="Пароль  пользователя",
     *                       type="string",
     *                   ),
     *                 @OA\Property(
     *                        property="name",
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

    public function createStaff(CreateStaffRequest $request )
    {
        return response()->json(
            app(StaffService::class)->create($request->toDto()),
            200
        );

    }
}