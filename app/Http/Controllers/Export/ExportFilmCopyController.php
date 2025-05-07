<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\Export\FilmCopy;
use Illuminate\Http\Request;
use App\Services\Export\ScheduleExportService;

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


    /**
     * @OA\Get(
     *     path="/api/export/schedule",
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
    public function exportSchedule(Request $request)
    {

        return app(ScheduleExportService::class)->run();
    }


}
