<?php
declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\User\UserDto;
use Carbon\Carbon;
class UpdateUserRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'gender' => 'required|integer',
            'phone' => 'required|string',
            'birthday' => 'required|string',
            'fio' => 'required|string',

        ];
    }


    public function toDto():UserDto {

        return new UserDto(
            $this->input('fio'),
            $this->input('email'),
            $this->input('phone'),
            $this->input('birthday'),
            $this->input('gender'),
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