<?php

namespace App\Services\FilmCopy;

use Carbon\Carbon;
use App\Repositories\HandBook\BannerRepository;
use App\Services\Paginator\PaginatorService;
use Illuminate\Support\Collection;
use App\Services\FileService\ImgService;
use App\Services\FileService\VideoService;
use Illuminate\Http\UploadedFile;
use App\Repositories\Films\FilmCopyRepository;
use App\Repositories\Films\ScheduleRepository;
use App\Dto\FilmCopy\FilmCopyDto;
use App\Dto\FilmCopy\FilmCopyOldDto;
use App\Repositories\HandBook\FileRepository;

class FilmCopyServices
{
    public function __construct(
        protected FilmCopyRepository $filmCopyRepository,
        protected ScheduleRepository $scheduleRepository,
        protected ScheduleServices $scheduleServices,
        protected PaginatorService $paginatorService,
        protected ImgService       $imgService,
        protected VideoService       $videoService,
        protected FileRepository     $fileRepository,
    ) {
    }


    /**
     * @param int $page
     * @param string|null $search
     * @param string|null $sort
     * @return object|__anonymous@758
     */

    public function list(int $page, string | null $search, string | null $sort): object
    {
        if (empty($sort)) {

            $sortString = 'start_date';
            $typeSort = "DESC";
        } else {
            $sort = explode("_", $sort);
            $sortString = $sort[0] == 'start' ? 'start_date' : 'end_date';
            $typeSort = $sort[1] == 'up' ? "ASC" : "DESC";
        }

        $filmCopies = $this->filmCopyRepository->get($search, $sortString, $typeSort)->where('end_date', '>', Carbon::now());
        $filmCopies = $filmCopies->each(function ($filmCopy) {
            $filmCopy->name = str_replace(['Чапаев с нами','/'],'',$filmCopy->name);
            return $filmCopy;
        });
        return $this->paginatorService->toPagination($filmCopies, $page);
    }


    /**
     * @param int $id
     * @return object
     */
    public function get(int $id): object
    {

        $filmCopy = $this->filmCopyRepository->getById($id);
        $filmCopy->ps = !(empty($filmCopy->ps) || $filmCopy->ps == 0);
        $filmCopy->not_only_jazz = !(empty($filmCopy->not_only_jazz) || $filmCopy->not_only_jazz == 0);
        $filmCopy->publication = !(empty($filmCopy->publication) || $filmCopy->publication == 0);
        $filmCopy->retro = !(empty($filmCopy->retro) || $filmCopy->retro == 0);
        $banners = $this->imgService->get($filmCopy->id, 'banner-film-copy');
        $video = $this->videoService->get($filmCopy->id, 'video-film-copy');
        $filmCopy->bannerUrl = empty($banners) ? null : config('services.app_url').'/img/'.$banners->type.'/'.$banners->name;
        if (empty($video)) {
            $filmCopy->videoUrl = null;
        } else {
            $filmCopy->videoUrl = config('services.app_url').'/img/'.$video->type.'/'.$video->name;
        }
        return $filmCopy;
    }

    public function update(int $id, FilmCopyDto $dto, UploadedFile|null $bannerFile, UploadedFile|null $videoFile): bool
    {
        $filmCopy = $this->filmCopyRepository->getById($id);
        if ($bannerFile) {
            $filmCopy->banners = $this->imgService->update($filmCopy->id, 'banner-film-copy', $bannerFile);
        }
        if ($videoFile) {
            $this->videoService->update($filmCopy->id, 'video-film-copy', $videoFile);
        }
        return $this->filmCopyRepository->update($id, $dto);
    }

    public function updateMovie(int $id, string $nameFile)
    {
        $this->videoService->updateNotLoad($id, 'video-film-copy', $nameFile);
    }

    /**
     * @param int $id
     * @return FilmCopyOldDto
     */
    public function getFilmCopyById(int $id): FilmCopyOldDto
    {
        $filmCopy = $this->filmCopyRepository->getById($id);
        $banners = $this->imgService->get($filmCopy->id, 'banner-film-copy');
        $schedules = $this->scheduleRepository->getCurrentByFilmCopyId($filmCopy->external_film_copy_id);
        $schedules = $this->scheduleServices->getScheduleByGroupDateForFilm($schedules, $filmCopy);
        $video = $this->videoService->get($filmCopy->id, 'video-film-copy');
        return new FilmCopyOldDto(
            $filmCopy->id,
            str_replace(['Чапаев с нами','/'],'',$filmCopy->name),
            explode(",", $filmCopy->actors),
            empty($banners) ? "" : config('services.app_url').'/img/'.$banners->type.'/'.$banners->name,
            $filmCopy->start_date,
            $filmCopy->end_date,
            explode(",", $filmCopy->genre),
            $filmCopy->duration,
            $filmCopy->external_film_copy_id,
            explode(",", $filmCopy->directors),
            Carbon::parse($filmCopy->start_date)->format('Y'),
            explode(",", $filmCopy->country),
            $filmCopy->description ?? "",
            false,
            array_values($schedules->toArray()),
            "11111",
            empty($video) ? "" : config('services.app_url').'/img/'.$video->type.'/'.$video->name,
        );
    }


    public function getFilmCopy(string $type): Collection
    {
        $filmCopies = $this->$type();
        return $filmCopies->each(function ($filmCopy) {
            $filmCopy->name = str_replace(['Чапаев с нами','/'],'',$filmCopy->name);
            $posters = $this->imgService->get($filmCopy->id, 'banner-film-copy');
            if (empty($posters)) {
                $filmCopy->posters = null;
            } else {
                $filmCopy->posters = config('services.app_url').'/img/'.$posters->type.'/'.$posters->name;
            }
            return $filmCopy;
        })->where("posters", "!=", null);
    }


    /**
     * @return array
     */
    public function listRepositoryFile(): array
    {
        $dirFiles = scandir(public_path().'/img/video-film-copy');
        $files = $this->fileRepository->get()->where('type', 'video-film-copy')->pluck('name')->toArray();
        $response = [];
        foreach ($dirFiles as $dirFile) {
            if (($dirFile != '..') && ($dirFile != '.') && !in_array($dirFile, $files)) {
                $response[] =  $dirFile;
            }
        }
        return $response;
    }

    /**
     * @return Collection
     */
    protected function NotOnlyJazz(): Collection
    {
        return $this->filmCopyRepository->get(null)->where('end_date', '>', Carbon::now())->where('publication', 1)->where('not_only_jazz', true);
    }

    /**
     * @return Collection
     */
    protected function retro(): Collection
    {
        return $this->filmCopyRepository->get(null)->where('end_date', '>', Carbon::now())->where('publication', 1)->where('retro', true);
    }


    /**
     * @return Collection
     */
    protected function ps(): Collection
    {
        return $this->filmCopyRepository->get(null)->where('end_date', '>', Carbon::now())->where('publication', 1)->where('ps', true);
    }
    /**
     * @return Collection
     */
    protected function future(): Collection
    {
        return $this->filmCopyRepository->get(null)->where('start_date', '>', Carbon::now())->where('publication', 1);
    }

    /**
     * @return Collection
     */
    protected function current(): Collection
    {
        return $this->filmCopyRepository->get(null)->where('start_date', '<', Carbon::now())->where('end_date', '>', Carbon::now())->where('publication', 1);

    }

}
