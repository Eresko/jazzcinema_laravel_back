<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Export\FilmCopy;
use Illuminate\Http\Request;

class ExportFilmCopyController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/export/export-filmcopy",
     *     tags={"Export"},
     *     summary="Export filmcopy",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function exportFilmCopy(Request $request)
    {

        return app(FilmCopy::class)->run();
    }


}
