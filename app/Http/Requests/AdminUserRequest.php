<?php

namespace App\Http\Requests;

use App\Rules\CanAccessLevel;
use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd(request()->all());
        return [
            'name' => ['required', 'string'],
            'email' => ['nullable', 'email'],
            'levels' => [
                'required',
                'array',
                'exists:admin_user_levels,id',],
            'levels' => ['array', 'required'],
            'levels.*' => ['integer', new CanAccessLevel($this->user())]
        ];
    }
}
