<?php
declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\User\UpdateStaffDto;
use Carbon\Carbon;
class CreateStaffRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
            'name' => 'required|string',

        ];
    }


    public function toDto():UpdateStaffDto {

        return new UpdateStaffDto(
            $this->input('name'),
            $this->input('password'),
            $this->input('email')
        );


    }

    public function toArray() {
        $massive = [];
        foreach ($this->rules() as $key => $item) {
            if (!empty($this->input($key))) {
                $massive[$key] = $this->input($key);
            }
        }
        return $massive;
    }
}