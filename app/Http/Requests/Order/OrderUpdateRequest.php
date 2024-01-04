<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'payment_type' => 'required|exists:payment_types,id',
            'comment' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:200',
            'time' => 'nullable|string|max:200',
            'is_delivery' => 'required|integer',
            'time_issue' => 'required|integer',
        ];
    }
}
