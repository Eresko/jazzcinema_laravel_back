<?php

namespace App\Services\Export;


use Carbon\Carbon;

use App\Repositories\Films\FilmCopyRepository;

class FilmCopy
{

    public function __construct(protected FilmCopyRepository $filmCopyRepository)
    {
    }


    public function run()
    {
        $filmCopiesExport = $this->getFilmCopy();
        $where = implode(",", array_column($filmCopiesExport,'id'));
        $filmCopyIds = $this->getId($where);

        foreach ($filmCopiesExport as &$item) {
            $item['filmcopyId'] = $filmCopyIds[$item['id']];
        }
        $filmCopies = $this->filmCopyRepository->get()->toArray();
        foreach ($filmCopiesExport aS $filmCopy) {
            if (!in_array($filmCopy['filmcopyId'],array_column($filmCopies,'external_film_copy_id'))) {
                $this->filmCopyRepository->create($filmCopy);
            }
        }

        return $filmCopiesExport;

    }


    /**
     * @param string $where
     * @return array
     */
    protected function getId(string $where):array {
        $url = config('services.api_ticket_soft').'filmCopy/'.$where;
        $out = $this->getCurl($url);
        $filmCopyIds = [];
        foreach (json_decode($out) as $row) {
            $filmCopyIds[$row->FilmID] = $row->Id;
        }
        return $filmCopyIds;
    }
    protected function getFilmCopy() {
        $age = $this->getAge();

        $currentDate = date('Y-m-d 00:00:00');
        $second = strtotime($currentDate);
        $currentDate = date('Y-m-d', $second);
        $filmCopies = [];
        foreach (json_decode($this->getCurl(config('services.api_ticket_soft').'film/'.$currentDate)) as $row) {
            $filmCopies[] = [
                'id' => $row->Id,
                'name' => $row->Name,
                'hire' => $row->Description,
                'releasedate' => $row->ReleaseDate,
                'duration' => $row->Duration,
                'url' => $row->FilmWebsite,
                'disabled' => $row->Disabled,
                'age' => $age[(string)$row->AgeLimitationID],
            ];

        }
        return $filmCopies;
    }
    /**
     * @return array
     */

    protected function getAge():array {
        $out = $this->getCurl(config('services.api_ticket_soft').'age');
        file_put_contents(storage_path().'/A_TOKEN_3.log', print_r($out, true ), FILE_APPEND | LOCK_EX); // вывод информации
        $age = [];
        if (!empty($out) && (count(json_decode($out))>0)) {
            foreach (json_decode($out) as $item) {
                $age [(int)$item->Id] = [
                    'name' => $item->Name,
                    'display' => $item->DisplayName,
                    'fullText' => $item->Description
                ];
            }

        }
        return $age;
    }

    protected function getCurl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
        return $out;
    }
}