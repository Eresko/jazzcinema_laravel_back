<?php
declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\FilmCopy\FilmCopyDto;
use Carbon\Carbon;
class UpdateHallRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'is_display_schedule' => 'required',

        ];
    }
}