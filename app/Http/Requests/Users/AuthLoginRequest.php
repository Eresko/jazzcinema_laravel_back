<?php
declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class AuthLoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'login' => 'required|string',
            'password' => 'required|string',
        ];
    }


}