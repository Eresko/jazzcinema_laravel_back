<?php

namespace App\Http\Controllers\FilmCopy;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilmCopy\ReservationNotSelectRequest;
use App\Http\Requests\FilmCopy\PaymentNotSelectRequest;
use Illuminate\Http\Response;
use App\Services\FilmCopy\BookingServices;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/booking/reservation-not-select",
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
    public function reservationNotSelect(ReservationNotSelectRequest $request)
    {

        return response()->json(
            app(BookingServices::class)->reservationNotSelect($request->toDto()),
            200
        );

    }


    public function paymentRegistration(PaymentNotSelectRequest $request) {
        return response()->json(
            app(BookingServices::class)->paymentNotSelect($request->toDto()),
            200
        );
    }


    public function checkTicket($id) {
        return response()->json(
            app(BookingServices::class)->checkTicket((int) $id),
            200
        );
    }

    public function repay($id) {
        return response()->json(
            app(BookingServices::class)->repay((int) $id),
            200
        );
    }

}
