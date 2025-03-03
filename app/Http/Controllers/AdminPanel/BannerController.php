<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\HandBook\BannerServices;
use Illuminate\Http\Request;
class BannerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/get-banners",
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
    public function getListBanner(Request $request)
    {
        return response()->json(
            app(BannerServices::class)->list(empty($request->pages) ? 1 : (int)$request->pages),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/admin-panel/banner/{id}",
     *     tags={"Админ панель"},
     *     summary="Получение баннера по id",
     *     @OA\Parameter(
     *       name="query",
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
    public function getBanner(Request $request,$id)
    {
        return response()->json(
            app(BannerServices::class)->get((int)$id),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/create-banner",
     *      security={{"bearerAuth":{}}},
     *     tags={"Админ панель"},
     *     summary="Создание банера",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"name","file","dateStart","dateStop"},
     *                 @OA\Property(
     *                     property="name",
     *                     description="Имя",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                      property="file",
     *                      description="file",
     *                      type="file",
     *                  ),
     *                  @OA\Property(
     *                       property="dateStart",
     *                       description="file",
     *                       type="string",
     *                  ),
     *                  @OA\Property(
     *                        property="dateStop",
     *                        description="file",
     *                        type="string",
     *                  ),
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
    public function create(Request $request)
    {

        return app(BannerServices::class)->create($request->name,$request->file('file'),$request->dateStart,$request->dateStop);

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/update-banner/{id}",
     *      security={{"bearerAuth":{}}},
     *     tags={"Админ панель"},
     *     summary="Изменение банера",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"name","file","dateStart","dateStop"},
     *                 @OA\Property(
     *                     property="name",
     *                     description="Имя",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                      property="file",
     *                      description="file",
     *                      type="file",
     *                  ),
     *                  @OA\Property(
     *                       property="dateStart",
     *                       description="file",
     *                       type="string",
     *                  ),
     *                  @OA\Property(
     *                        property="dateStop",
     *                        description="file",
     *                        type="string",
     *                  ),
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
    public function update(Request $request,$id)
    {

        return app(BannerServices::class)->update((int)$id,$request->name,empty($request->file('file')) ? null : $request->file('file') , $request->dateStart,$request->dateStop);

    }


}