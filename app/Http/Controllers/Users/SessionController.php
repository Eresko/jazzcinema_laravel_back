<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Services\Users\UsersServices;



class SessionController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users/privilege",
     *     tags={"USERS"},
     *     summary="Привелегии пользователя",
     *     @OA\Response(
     *         response="200",
     *         description="",
     *         @OA\MediaType(
     *              mediaType="application/json"
     *         )
     *     )
     * )
     */
    public function getPrivilege(Request $request)
    {
        return response()->json(
            app(UsersServices::class)->getPrivilege(),
            200
        );

    }

}