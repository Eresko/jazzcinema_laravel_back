<?php

namespace App\Services\Export;


use Carbon\Carbon;
use App\Dto\Schedule\ScheduleExportDto;
use App\Repositories\Films\ScheduleRepository;

class ScheduleExportService
{

    public function __construct(
        protected ScheduleRepository $scheduleRepository,
        protected CursService $cursService
    )
    {
    }

    /**
     * @return void
     */
    public function run():void {
        $performanceTimeFrames = $this->getPerformanceTimeFrame();
        $performance = $this->getPerformance(array_column($performanceTimeFrames,'PerformanceID'));
        $structureElements = $this->getStructureElements(array_column($performance,'StructureElementID'));
        $filmCopy = $this->getPerformanceFilmCopy(array_column($performanceTimeFrames,'PerformanceID'));
        $schedules = $this->parseSchedule($performanceTimeFrames,$filmCopy,$performance,$structureElements);
        $this->updateSchedule($schedules);
    }

    /**
     * @param array $schedules
     * @return void
     */
    protected function updateSchedule(array $schedules):void {
        foreach ($schedules as $schedule) {
            $this->scheduleRepository->create($schedule);
        }
    }

    /**
     * @param array $performanceTimeFrames
     * @param array $filmCopy
     * @param array $performance
     * @param array $structureElements
     * @return array
     */
    protected function parseSchedule(array $performanceTimeFrames,array $filmCopy,array $performance,array $structureElements):array {

        $schedules = [];
        foreach ($performanceTimeFrames as $performanceTimeFrame) {
            $schedules[] = new ScheduleExportDto(
                intval($performance[$performanceTimeFrame->PerformanceID]->StructureElementID),
                Carbon::parse($performanceTimeFrame->StartTime)->format('H:i:s'),
                //date('H:i:s',strtotime($performanceTimeFrame->StartTime)),
                $performance[$performanceTimeFrame->PerformanceID]->BasePrice,
                Carbon::parse($performanceTimeFrame->StartTime)->format('Y-m-d'),
                //date('Y-m-d',strtotime($performanceTimeFrame->StartTime)),
                $filmCopy[$performanceTimeFrame->PerformanceID]->FilmCopyID,
                $performance[$performanceTimeFrame->PerformanceID]->Id,
                $structureElements[$performance[$performanceTimeFrame->PerformanceID]->StructureElementID]->Name
            );
        }
        return $schedules;

    }
    /**
     * @param array $ids
     * @return array
     */
    protected function getPerformanceFilmCopy(array $ids):array {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'performance-film-copy', ['zapros' => implode(",",$ids)]);
        $filmCopy = [];
        foreach (json_decode($resultStr) as $row) {
            $filmCopy[$row->PerformanceID] = $row;
        }
        return $filmCopy;
    }
    /**
     * @param array $ids
     * @return array
     */
    protected function getStructureElements(array $ids):array {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'structure-element', ['zapros' => implode(",",$ids)]);
        $halls = [];
        foreach (json_decode($resultStr) as $row) {
            $halls[$row->Id] = $row;
        }
        return $halls;
    }
    /**
     * @param array $ids
     * @return array
     */
    protected function getPerformance(array $ids):array {
        $resultStr = $this->cursService->post(config('services.api_ticket_soft') . 'performance', ['zapros' => implode(",",$ids)]);
        $performance = [];
        foreach (json_decode($resultStr) as $row) {
            $performance[$row->Id] = $row;
        }
        return $performance;
    }

    /**
     * @return array
     */
    protected function getPerformanceTimeFrame():array {
        $data = [  'dateCurrent' => date('Y-m-d')];
        $resultStr = $this->cursService->post(config('services.api_ticket_soft').'performance-time-frame',$data);
        $performanceTimeFrames = [];
        foreach (json_decode($resultStr) as $row) {
            $performanceTimeFrames[] = $row;
        }
        return $performanceTimeFrames;
    }
}