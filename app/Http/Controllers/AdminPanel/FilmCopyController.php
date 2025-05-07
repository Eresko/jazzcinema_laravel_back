<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\HandBook\BannerServices;
use App\Services\FilmCopy\FilmCopyServices;
use Illuminate\Http\Request;
use App\Http\Requests\AdminPanel\FilmCopyUpdateRequest;
use App\Http\Requests\AdminPanel\MovieUpdateRequest;

class FilmCopyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin-panel/get-film-copies",
     *     tags={"Админ панель"},
     *     summary="Получение списка баннеров",
     *     @OA\Parameter(
     *       name="page",
     *       in="query",
     *       @OA\Schema(
     *               type="integer"
     *           )
     *      ),
     *      @OA\Parameter(
     *        name="sort",
     *        in="query",
     *        @OA\Schema(
     *                type="string"
     *            )
     *       ),
     *     @OA\Parameter(
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
            app(FilmCopyServices::class)->list(
                empty($request->pages) ? 1 : (int)$request->pages,
                strlen($request->search) < 2 ? null : $request->search,
                empty($request->sort) ? null : $request->sort
            ),
            200
        );

    }

    /**
     * @OA\Get(
     *     path="/api/admin-panel/get-film-copy/{id}",
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
    public function getFilmCopy(Request $request, $id)
    {
        return response()->json(
            app(FilmCopyServices::class)->get((int)$id),
            200
        );

    }


    /**
     * @OA\Post(
     *     path="/api/admin-panel/update-film-copy/{id}",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление фильмокопии",
     *     tags={"Админ панель"},
     *     summary="Store",
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
    public function update(FilmCopyUpdateRequest $request, $id)
    {

        return app(FilmCopyServices::class)->update(
            (int)$id,
            $request->toDto(),
            empty($request->file('banner')) ? null : $request->file('banner'),
            empty($request->file('video')) ? null : $request->file('video'),
        );

    }
    /**
     * @OA\Get(
     *     path="/api/admin-panel/get-movies",
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
    public function getMovies(Request $request)
    {
        return response()->json(
            app(FilmCopyServices::class)->listRepositoryFile(
                empty($request->pages) ? 1 : (int)$request->pages
            ),
            200
        );

    }

    /**
     * @OA\Post(
     *     path="/api/admin-panel/update-film-copy",
     *      security={{"bearerAuth":{}}},
     *     summary="Обновление фильмокопии",
     *     tags={"Админ панель"},
     *     summary="Store",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *                 type="object",
     *                 required={"name","id","name"},
     *                 @OA\Property(
     *                     property="id",
     *                     description="id фильмокопии",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                      property="name",
     *                      description="Название файла",
     *                      type="file",
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
    public function updateMovies(MovieUpdateRequest $request)
    {

        return app(FilmCopyServices::class)->updateMovie(
            (int)$request->id,
            $request->name,
        );

    }

}
