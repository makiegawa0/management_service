<?php

namespace App\Http\Requests\Api;

use App\Models\PaymentRequest;
use App\Repositories\PayInRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestUpdateRequest extends FormRequest
{
    public function __construct()
    {
        parent::__construct();

        // $this->campaigns = $campaigns;

        Validator::extendImplicit('valid_payin_id', function ($attribute, $value, $parameters, $validator) {
            return request()->filled('payin_id') ? (new PayInRepository)->find(request()->get('payin_id')) : true;
        });
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payin_id' => ['nullable'],
            'issue_callback' => ['nullable', 'boolean']
        ];
    }
}
