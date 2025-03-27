<?php

namespace App\Http\Controllers\Banners;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\HandBook\BannerServices;

class BannerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/banner",
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
    public function getBanners(Request $request)
    {
        return response()->json(
            ['result' => array_values(app(BannerServices::class)->getBanners()->toArray())],
            200
        );

    }
}
