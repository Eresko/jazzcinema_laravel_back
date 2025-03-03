<?php
declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\Http\Requests\BaseRequest;
use App\Dto\User\MessageDto;

class PushMessageRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'message' => 'required|string',
            'format' => 'required|string',
            'theme' => 'required|string',
            'photo' => 'sometimes|file',
        ];
    }
    
    public function toDto() {
        return new MessageDto(
          $this->input('email'),
          $this->input('message'),
          $this->input('format'),
          $this->input('theme'),
          $this->input('photo'),

        );
    }


}