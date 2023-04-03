<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required'],
            'leader_id' => ['required'],
            'project_name' => ['required', 'string', 'max:255'],
            'start_date' => ['required'],
            'end_date' => ['required'],
            'progress' => ['required', 'array'],
            'progress.*.progress_name' => ['required', 'string', 'max:255'],
            'progress.*.isFinished' => ['boolean'],
            'progress.*.id' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'leader_id.required' => 'The leader field is required.',
            'client_id.required' => 'The client field is required.',
            'progress.*.progress_name.required' => 'The progress name field is required.'
        ];
    }
}
