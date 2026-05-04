<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackVisitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_agent' => 'nullable|string|max:500',
            'page_url' => 'nullable|string|max:2048',
            'screen_resolution' => 'nullable|string|max:50',
        ];
    }
}
