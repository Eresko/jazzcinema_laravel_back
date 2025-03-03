<?php
declare(strict_types=1);

namespace App\Http\Requests\FilmCopy;

use App\Http\Requests\BaseRequest;
use App\Dto\FilmCopy\ReservationDto;

class ReservationNotSelectRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'idPerformance' => 'required|string',
            'zalId' => 'required|integer',
            'selectIds' => 'required',
        ];
    }
    
    
    public function toDto() {
        return new ReservationDto(
            (int)$this->input('idPerformance'),
            $this->input('zalId'),
            $this->input('selectIds'),
        );
    }


}