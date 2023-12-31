<?php

namespace App\Http\Requests;

use App\Rules\TimeOrderRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'number_of_devices' => 'required|numeric|min:0',
            'payment_type' => 'nullable|exists:payment_types,id',
            'comment' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:191',
            'is_delivery' => 'integer',
            'time_issue' => ['integer', new TimeOrderRule()],
        ];
    }
}
