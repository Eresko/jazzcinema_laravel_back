<?php

namespace App\Http\Controllers\FilmCopy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\FilmCopy\FilmCopyServices;
use Illuminate\Http\Request;

class FilmCopyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/film-copy/current",
     *     tags={"Фильмокопии"},
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
    public function getFilmCopyCurrent(Request $request)
    {
        return response()->json(
            array_values(app(FilmCopyServices::class)->getFilmCopy('current')->toArray()),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/future",
     *     tags={"Фильмокопии"},
     *     summary="Получение списка фильмов предстоящих в прокате",
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
    public function getFilmCopyFuture(Request $request)
    {
        return response()->json(
            array_values(app(FilmCopyServices::class)->getFilmCopy('future')->toArray()),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/ps",
     *     tags={"Фильмокопии"},
     *     summary="Получение списка фильмов в киноклубе",
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
    public function getFilmCopyPs(Request $request)
    {
        return response()->json(
            array_values(app(FilmCopyServices::class)->getFilmCopy('ps')->toArray()),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/retro",
     *     tags={"Фильмокопии"},
     *     summary="Получение списка фильмов ретро",
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
    public function getFilmCopyRetro(Request $request)
    {
        return response()->json(
            array_values(app(FilmCopyServices::class)->getFilmCopy('retro')->toArray()),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/not-only-jazz",
     *     tags={"Фильмокопии"},
     *     summary="Получение списка фильмов концертов",
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
    public function getFilmCopyNotOnlyJazz(Request $request)
    {
        return response()->json(
            array_values(app(FilmCopyServices::class)->getFilmCopy('NotOnlyJazz')->toArray()),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/film-copy/specific/{id}",
     *     tags={"Фильмокопии"},
     *     summary="Получение фильма по id",
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
    public function getFilmCopy(Request $request, $id)
    {
        return response()->json(
            app(FilmCopyServices::class)->getFilmCopyById($id),
            200
        );

    }


}
