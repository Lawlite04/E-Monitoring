<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreLeaderRequest extends FormRequest
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
            'leader_name' => ['required', 'string', 'max:255'],
            'leader_email' => ['required', 'email', 'max:255', 'unique:leaders'],
            // 'leader_telp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'leader_telp' => ['required'],
            'leader_avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }
}
