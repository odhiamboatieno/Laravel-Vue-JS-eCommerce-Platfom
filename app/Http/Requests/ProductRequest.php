<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        // checking is it in update mode then some field may be not
        if ($this->id) {
            return [
                'product_name'  => 'required',
                'category'      => 'required',
                'sub_category'  => 'required',
                'quantity'      => 'required|integer',
                'buying_price'  => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'selling_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'image'         => 'nullable|image64:jpeg,png,gif,jpg,webp,bmp',
                'attachments.*' => 'nullable|mimes:jpeg,png,gif,jpg,webp,bmp',
            ];
        } else {
            return [
                'product_name'  => 'required',
                'category'      => 'required',
                'sub_category'  => 'required',
                'quantity'      => 'required|integer',
                'buying_price'  => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'selling_price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
                'image'         => 'required|image64:jpeg,png,gif,jpg,webp,bmp',
                'attachments.*' => 'nullable|mimes:jpeg,png,gif,jpg,webp,bmp',
            ];
        }

    }

    public function message()
    {
        return [
            'image.required'          => 'Feature Image Is Required',
            'image.image64'           => 'Feature Image must to be a type of jpeg,png,gif,jpg,webp,bmp',
            'image.attachments.mimes' => 'Feature Image must have to be a  type of jpeg,png,gif,jpg,webp,bmp',

        ];
    }
}
