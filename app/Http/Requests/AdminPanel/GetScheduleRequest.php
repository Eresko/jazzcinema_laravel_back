<?php

declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\Schedule\GetScheduleDto;
use Carbon\Carbon;

class GetScheduleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'page' => 'sometimes|integer',
            'search' => 'sometimes',
            'start' => 'required|string',
            'end' => 'required|string',

        ];
    }


    public function toDto(): GetScheduleDto
    {
        return new GetScheduleDto(
            empty($this->input('page')) ? 1 : (int)$this->input('page'),
            !empty($this->input('search')) && strlen($this->input('search')) < 2 ? null : $this->input('search'),
            Carbon::parse($this->input('start')),
            Carbon::parse($this->input('end')),
        );
    }
}
