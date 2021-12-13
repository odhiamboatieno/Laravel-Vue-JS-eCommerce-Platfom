<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name'           => 'required',
            'phone'          => 'required',
            // 'phone'          => 'required|regex:/(01)[0-9]{9}/',
            'delivery_area'  => 'required',
            'delivery_date'  => 'sometimes|required',
            'delivery_time'  => 'sometimes|required',
            'address'        => 'required',
            'payment_method' => 'required',
            'cart_total'     => 'required|numeric|gt:0',
            'card_no'        => 'required_if:payment_method,3',
            'cvvNumber'      => 'required_if:payment_method,3|max:4',
            'expire_month'   => 'nullable|required_if:payment_method,3|date_format:m|size:2',
            'expire_year'    => 'nullable|required_if:payment_method,3|date_format:Y|size:4',
        ];
    }

    public function messages()
    {
        return [
            'name.required'            => 'name required',
            'phone.required'           => 'required',
            'phone.regex'              => 'invalid',
            'phone.size'               => 'invalid size',
            'payment_method.required'  => 'please chose a payment method',
            'card_no.required_if'      => 'Please Enter Card No',
            'cvvNumber.required_if'    => 'Please Enter CVC',
            'expire_month.required_if' => 'Please Enter Month',
            'expire_year.required_if'  => 'Please Enter Expired Year',
            'expire_month.date_format' => 'Invalid format try ex:04',
            'expire_year.date_format'  => 'Invalid format try ex:2030',
            'cart_total.*'             => 'Your Cart is Empty',
        ];
    }

}
