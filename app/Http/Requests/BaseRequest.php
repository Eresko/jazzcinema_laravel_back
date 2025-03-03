<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            '*.required' => 'The :attribute field is required',
            '*.string' => 'The :attribute field expects a string',
            '*.alpha_num' => 'The :attribute field expects only alphanumeric characters',
            '*.email' => 'The :attribute field expects an email address',
            '*.min' => 'The minimum length of :attribute must be :min characters',
            '*.max' => 'The maximum length of :attribute must not exceed :max characters',
            '*.unique' => 'The :attribute field is not unique',
            '*.alpha_dash' => 'The :attribute field may only contain letters, numbers, dashes, and underscores.'
        ];
    }
}
