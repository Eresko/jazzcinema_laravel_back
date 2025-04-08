<?php
declare(strict_types=1);

namespace App\Http\Requests\AdminPanel;

use App\Http\Requests\BaseRequest;
use App\Dto\FilmCopy\FilmCopyDto;
use Carbon\Carbon;
class FilmCopyUpdateRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'start_date' => 'required|string',
            'disabled' => 'required|string',
            'actors' => 'sometimes|string',
            'age' => 'sometimes|string',
            'country' => 'sometimes|string',
            'description' => 'sometimes|string',
            'genre' => 'sometimes|string',
            'memorandum' => 'sometimes|string',
            'rating' => 'sometimes|string',
            'producers' => 'sometimes|string',
            'not_only_jazz' => 'sometimes|boolean',
            'ps' => 'sometimes|boolean',
            'publication' => 'sometimes|boolean',
            'retro' => 'sometimes|boolean',
            'directors' => 'sometimes|string',
        ];
    }

    public function toDto() {

        return  new FilmCopyDto(
            Carbon::parse($this->input('start_date'))->format('Y-m-d'),
            Carbon::parse($this->input('disabled'))->format('Y-m-d'),
            $this->input('actors'),
            $this->input('age'),
            $this->input('country'),
            $this->input('description'),
            $this->input('genre'),
            $this->input('memorandum'),
            empty($this->input('rating')) ? "" : $this->input('rating'),
            $this->input('producers'),
            !empty($this->input('not_only_jazz')) && $this->input('not_only_jazz') == 1,
            !empty($this->input('ps')) && (bool)$this->input('ps') == 1,
            !empty($this->input('publication')) && $this->input('publication') == 1,
            empty($this->input('retro')) ? false : $this->input('retro'),
            empty($this->input('directors')) ? "" : $this->input('directors'),
        );



    }
}