<?php

namespace App\Http\Requests;

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

        $rules = [
            'number_of_devices' => 'required|numeric|min:0',
//            'time_issue' => 'required|numeric|min:1|max:2',
            'payment_type' => 'nullable|exists:payment_types,id',
            'comment' => 'nullable|string|max:200',
            'address' => 'nullable|string|max:191',
            'is_delivery' => 'integer',
            'is_time' => 'integer'
        ];
//        if(request('time_issue') == 2){
//            $rules['time'] = 'required|date_format:H:i';
//        }

        return $rules;
    }
}
