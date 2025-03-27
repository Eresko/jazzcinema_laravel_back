<?php

namespace App\Http\Controllers\HandBook;

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
     *     path="/api/hand-book/banner",
     *     tags={"Справочник"},
     *     summary="Получение баннеров на главной",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getBanner(Request $request)
    {
        return app(BannerServices::class)->get();
    }


}
