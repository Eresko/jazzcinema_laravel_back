<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginManagerRequest;
use Illuminate\Http\Response;
use Firebase\JWT\JWT;
use App\Services\HandBook\BannerServices;
use Illuminate\Http\Request;
use App\Services\Export\CursService;


class ImportController extends Controller
{

    public function performanceTimeFrame(Request $request)
    {
        return json_decode(app(CursService::class)
            ->post(config('services.api_ticket_soft') . 'performance-time-frame/',['dateCurrent' => $request->dateCurrent]));

    }


    public function performanceFilmCopy(Request $request)
    {

        return json_decode(app(CursService::class)
            ->post(config('services.api_ticket_soft') . 'performance-film-copy/', ['zapros' => $request->zapros]));


    }

    public function performance(Request $request)
    {

        return json_decode(app(CursService::class)->post(config('services.api_ticket_soft') . 'performance/', ['zapros' => $request->zapros]));


    }

    public function age(Request $request)
    {

        return json_decode(app(CursService::class)->get(config('services.api_ticket_soft') . 'age/'));


    }
    public function film(Request $request,$film)
    {

        return json_decode(app(CursService::class)->get(config('services.api_ticket_soft') . 'film/'.$film));


    }

    public function filmCopy(Request $request)
    {

        return json_decode(app(CursService::class)->get(config('services.api_ticket_soft') . 'filmCopy/'.$request->date));


    }




}