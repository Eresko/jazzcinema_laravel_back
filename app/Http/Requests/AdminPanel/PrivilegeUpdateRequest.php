<?php
declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\User\PrivilegeDto;
use Carbon\Carbon;
class PrivilegeUpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'specifiedReservationLimit' => 'required|integer',
            'specifiedSalesLimit' => 'required|integer',
            'salesAllowed' => 'required|integer',

        ];
    }


    public function toDto():PrivilegeDto {
        return new PrivilegeDto(
            (int)$this->input('specifiedReservationLimit'),
            0,
            0,
            (int)$this->input('specifiedSalesLimit'),
            (bool)$this->input('salesAllowed'),
        );
    }
}