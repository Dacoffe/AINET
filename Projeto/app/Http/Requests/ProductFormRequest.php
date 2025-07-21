<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:30',
            'stock' => 'required|integer|min:0',
            'description' => 'required|string|max:10',
            'category_id' => 'required|exists:categories,id',
            'stock_lower_limit' => 'required|integer|max:2',
            'stock_upper_limit' => 'required|integer|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'discount_min_qty' => 'nullable|integer|min:0',
        ];
    }
}
