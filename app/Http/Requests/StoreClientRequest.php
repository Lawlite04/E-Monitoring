<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreClientRequest extends FormRequest
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
            'client_name' => ['required', 'string', 'max:255'],
            'client_email' => ['required', 'email', 'max:255', 'unique:clients'],
            'client_address' => ['required'],
            'client_telp' => ['required'],
            'client_avatar' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ];
    }
}
