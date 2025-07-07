<?php

namespace App\Services\FilmCopy;

use App\Repositories\Halls\HallRepository;
use App\Services\Paginator\PaginatorService;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use App\Repositories\Films\ScheduleRepository;
use App\Repositories\Films\FilmCopyRepository;
use App\Dto\FilmCopy\FilmCopyByScheduleDto;
use App\Dto\Schedule\ScheduleDto;
use App\Services\Export\PerformanceStatus;
use App\Dto\Schedule\GetScheduleDto;

class ScheduleServices
{
    public function __construct(
        protected FilmCopyRepository $filmCopyRepository,
        protected ScheduleRepository $scheduleRepository,
        protected PaginatorService $paginatorService,
        protected PerformanceStatus  $performanceStatus,
        protected HallRepository $hallRepository,
    ) {
    }

    /**
     * @return Collection
     */
    public function getScheduleByGroupDate(): Collection
    {

        $schedules = $this->scheduleRepository->getCurrent();
        $halls = $this->hallRepository->getAll()->where('is_display_schedule', 0);
        $films = $this->filmCopyRepository->getByFilmCopyExternalIds($schedules->pluck("external_film_copy_id"));
        $schedules = $schedules->whereNotIn('external_film_copy_id', $films->where('publication', 0)->pluck('external_film_copy_id'))->whereNotIn('structure_element_id', $halls->pluck('structure_id'));
        $schedules = $schedules->groupBy("start_date");
        return $schedules->map(function ($schedule) use ($films) {
            $filmCopySchedules = $schedule->groupBy("external_film_copy_id");
            return [
                'day' => Carbon::parse($schedule->first()->start_date)->format('d.m.Y'),
                'schedule' => array_values($this->getSchedule($filmCopySchedules, $films)->toArray())
            ];
        });
    }

    /**
     * @param Collection $filmCopySchedules
     * @return Collection
     */
    public function getScheduleByGroupDateForFilm(Collection $filmCopySchedules): Collection
    {
        $filmCopySchedules = $filmCopySchedules->groupBy("start_date");
        return $filmCopySchedules->map(function ($schedule) {
            return [
                'day' => Carbon::parse($schedule->first()->start_date)->format('d.m.Y'),
                'schedule_time' => $this->getTimeSchedule($schedule)
            ];
        });
    }

    /**
     * @param int $performanceId
     * @return ScheduleDto
     */
    public function getScheduleByPerformance(int $performanceId): ScheduleDto
    {
        $schedule = $this->scheduleRepository->getByExternalId($performanceId);
        $film = $this->filmCopyRepository->getByFilmCopyExternalId($schedule->external_film_copy_id);
        return new ScheduleDto(
            $schedule->external_performance_id,
            $schedule->structure_element_id,
            $schedule->price,
            Carbon::parse($schedule->start_date . ' ' . $schedule->start_time)->format('H:i'),
            Carbon::parse($schedule->start_date . ' ' . $schedule->start_time)->format('d.m.Y H:i'),
            $schedule->hall,
            strtotime($schedule->start_date . ' ' . $schedule->start_time),
            str_replace(['Чапаев с нами','/'],'',$film->name),
        );
    }


    /**
     * @param GetScheduleDto $dto
     * @return ScheduleDto
     */
    public function getScheduleByFilter(GetScheduleDto $dto)
    {
        $schedule = $this->scheduleRepository->getByDates($dto->startTime, $dto->endTime);
        $films = $this->filmCopyRepository->getByFilmCopyExternalIds($schedule->pluck('external_film_copy_id'), $dto->search);
        $schedule =  $schedule->map(function ($scheduleItem) use ($films) {
            $film = $films->where('external_film_copy_id', $scheduleItem->external_film_copy_id)->first();
            if (empty($film->name)) {
                return null;
            }
            return new ScheduleDto(
                $scheduleItem->external_performance_id,
                $scheduleItem->structure_element_id,
                $scheduleItem->price,
                Carbon::parse($scheduleItem->start_date . ' ' . $scheduleItem->start_time)->format('H:i'),
                Carbon::parse($scheduleItem->start_date . ' ' . $scheduleItem->start_time)->format('d.m.Y H:i'),
                $scheduleItem->hall,
                strtotime($scheduleItem->start_date . ' ' . $scheduleItem->start_time),
                str_replace(['Чапаев с нами','/'],'',$film->name),
                $scheduleItem->id
            );
        })->whereNotNull();

        return $this->paginatorService->toPagination($schedule, $dto->page);
    }

    /**
     * @param int $performanceId
     * @param User|null $user
     * @return array
     *
     */
    public function getStatusByPerformance(int $performanceId, User | null $user = null): array
    {
        $schedule = $this->scheduleRepository->getByExternalId($performanceId);
        return $this->performanceStatus->getStatusPerformance($performanceId, $schedule->structure_element_id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->scheduleRepository->deleteById($id);
    }
    /**
     * @param Collection $filmCopySchedules
     * @param Collection $films
     * @return Collection
     */
    protected function getSchedule(Collection $filmCopySchedules, Collection $films): Collection
    {
        return $filmCopySchedules->map(function ($filmCopySchedule) use ($films) {
            $film = $films->where('external_film_copy_id', $filmCopySchedule->first()->external_film_copy_id)->first();
            $schedule = $this->getTimeSchedule($filmCopySchedule);
            if ((empty($schedule)) || empty($film)) {
                return null;
            }
            return new FilmCopyByScheduleDto(
                str_replace(['Чапаев с нами','/'],'', $film->name),
                $film->external_film_copy_id,
                $film->id,
                $film->genre ?? "",
                $schedule
            );
        })->whereNotNull();
    }

    /**
     * @param Collection $filmCopySchedule
     * @return array
     */
    protected function getTimeSchedule(Collection $filmCopySchedule): array
    {
        $times = [];
        foreach ($filmCopySchedule as $itemSchedule) {
            if (Carbon::parse($itemSchedule->start_date . ' ' . $itemSchedule->start_time)->lt(Carbon::now())) {
                continue;
            }
            $times[] = new ScheduleDto(
                $itemSchedule->external_performance_id,
                $itemSchedule->structure_element_id,
                $itemSchedule->price,
                Carbon::parse($itemSchedule->start_date . ' ' . $itemSchedule->start_time)->format('H:i'),
                Carbon::parse($itemSchedule->start_date . ' ' . $itemSchedule->start_time)->format('d.m.Y H:i'),
                $itemSchedule->hall,
                strtotime($itemSchedule->start_date . ' ' . $itemSchedule->start_time)
            );
        }
        return $times;
    }


}
