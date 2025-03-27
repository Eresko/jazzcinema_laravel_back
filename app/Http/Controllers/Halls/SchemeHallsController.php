<?php

namespace App\Http\Controllers\Halls;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginUserRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\Halls\HallServices;

class SchemeHallsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/halls/",
     *     tags={"ЗАЛ"},
     *     summary="Схема зала всеъ залов",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getAllHalls(Request $request)
    {

        return app(HallServices::class)->getScheme();
    }

    /**
     * @OA\Get(
     *     path="/api/halls/{id}",
     *     tags={"ЗАЛ"},
     *     summary="Схема определенного зала но номеру",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function get(Request $request, $id)
    {

        return app(HallServices::class)->get($id);
    }

    /**
     * @OA\Get(
     *     path="/api/scheme/{performance}",
     *     tags={"ЗАЛ"},
     *     summary="Схема зала по сеансу",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function byPerformance(Request $request, $performance)
    {
        return response()->json(
            app(HallServices::class)->getByPerformance((int)$performance),
            200
        );
    }



}
