<?php
declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;

class AuthPhoneRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'phone' => 'required|string',
        ];
    }


}