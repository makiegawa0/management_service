<?php

namespace App\Http\Requests\Api;

use App\Repositories\PayInRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestStoreRequest extends FormRequest
{
    public function __construct()
    {
        parent::__construct();

        // $this->campaigns = $campaigns;

        Validator::extendImplicit('valid_payin_id', function ($attribute, $value, $parameters, $validator) {
            return request()->filled('payin_id') ? (new PayInRepository)->find(request()->get('payin_id')) : true;
        });

        Validator::extendImplicit('valid_user_id', function ($attribute, $value, $parameters, $validator) {
            return request()->has('user_id') && (new UserRepository)->find(request()->get('user_id'));
        });
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // \Log::info(auth('admin'));
        return [
            'payin_id' => ['nullable', 'valid_payin_id'],
            'amount' => ['nullable'],
            'user_id' => ['required', 'valid_user_id']
        ];
    }
}
