<?php

namespace App\Requests;

use App\Classes\Helper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;


class StoreCampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:500',
            'from' => 'required|date|after_or_equal:today',
            'to' => 'required|date|after_or_equal:from',
            'total_budget' => 'required|numeric',
            'daily_budget' => 'required|numeric',
            'creatives.*' => 'mimes:jpg,jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'creatives.mimes' => 'Banner type must be jpg,jpeg,png',
            'from.required' => 'Campaign start date is required',
            'from.after_or_equal' => 'Campaign start date must be greater or equal to today',
            'to.required' => 'Campaign end date is required',
            'to.after_or_equal' => 'Campaign end date must be greater or equal to start date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = Helper::errorResponse( $validator->errors()->first(), Helper::UNPROCESSABLE_CONTENT, "validation_error");
        throw new ValidationException($validator, $response);
    }
}
