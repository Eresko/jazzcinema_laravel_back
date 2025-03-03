<?php

namespace App\Services\Auth;


use Carbon\Carbon;
use App\Models\CampaignStreamerParticipation;
use App\Models\StreamerAccount;
use App\Models\TwitchSnapshot;
use App\Repositories\Campaign\GetCampaign;
use App\Services\BrandAnalyticsDto;
use App\Services\ImpressionsGamesService;
use App\Services\ImpressionsPlatformService;
use App\DTO\Brand\CampaignsDto;
use App\Repositories\Campaign\VisitRepository;
use App\Repositories\Campaign\CampaignSnapshotsRepositories;
use Firebase\JWT\JWT;

class CallService
{
    public function run(string $phone):string| array
    {
        $url = config('services.telephone_ip.url').'authcalls/'.config('services.telephone_ip.token').'/get_code/'.$phone.'/';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
        $out = json_decode($out);
        file_put_contents(storage_path().'/A_CODE_3.log', print_r($out, true ), FILE_APPEND | LOCK_EX); // вывод информации
        if (!empty($out->data->code)) {
            return $out->data->code;
        }
        return ['error' => $out->error];
    }
}
