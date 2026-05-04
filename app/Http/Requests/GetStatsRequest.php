<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetStatsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'days' => 'required|integer|min:1|max:365',
        ];
    }

    public function getDays(): int
    {
        return (int) $this->input('days', 7);
    }
}
