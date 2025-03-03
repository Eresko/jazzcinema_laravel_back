<?php
declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'fio' => 'sometimes|string',
            'email' => 'sometimes|string',
            'datebirth' => 'sometimes|string',
            'gender' => 'sometimes|number',
            'password' => 'sometimes|number',
        ];
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