<?php

namespace App\Http\Requests\Coupon;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'data' => $validator->errors()
            ], 400)
        );
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:50|unique:coupons,code',
            'name' => 'required|string|max:50',
            'type_coupon' => 'nullable|string|in:coupon,cashback,all,member',
            'type' => 'required|string|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'value_cashback' => 'nullable|numeric|min:0',
            'min_transaction' => 'required|numeric|min:0',
            'max_discount' => 'required|numeric|min:0',
            'usage_limit' => 'required|integer|min:1',
            'usage_per_user' => 'required|integer|min:1',
            'usage_count' => 'required|integer|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:0,1',
            'is_show' => 'required|in:0,1'
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'code.required' => 'The Code field is required.',
            'code.unique' => 'The Code has already been taken.',
            'name.required' => 'The Name field is required.',
            'type_coupon.in' => 'The Jenis field must be coupon, cashback, or all.',
            'type.required' => 'The Type field is required.',
            'type.in' => 'The Type field must be either percentage or fixed.',
            'value.required' => 'The Value field is required.',
            'value.numeric' => 'The Value field must be a number.',
            'value.min' => 'The Value field must be at least 0.',
            'value_cashback.numeric' => 'The Value Cashback field must be a number.',
            'value_cashback.min' => 'The Value Cashback field must be at least 0.',
            'min_transaction.required' => 'The Min Transaction field is required.',
            'min_transaction.numeric' => 'The Min Transaction field must be a number.',
            'min_transaction.min' => 'The Min Transaction field must be at least 0.',
            'max_discount.required' => 'The Max Discount field is required.',
            'max_discount.numeric' => 'The Max Discount field must be a number.',
            'max_discount.min' => 'The Max Discount field must be at least 0.',
            'usage_limit.required' => 'The Usage Limit field is required.',
            'usage_limit.integer' => 'The Usage Limit field must be an integer.',
            'usage_limit.min' => 'The Usage Limit field must be at least 1.',
            'usage_limit_per_user.required' => 'The Usage Limit Per User field is required.',
            'usage_per_user.integer' => 'The Usage Per User field must be an integer.',
            'usage_per_user.min' => 'The Usage Per User field must be at least 1.',
            'usage_count.required' => 'The Usage Count field is required.',
            'usage_count.integer' => 'The Usage Count field must be an integer.',
            'usage_count.min' => 'The Usage Count field must be at least 0.',
            'start_date.required' => 'The Start Date field is required.',
            'start_date.date' => 'The Start Date field must be a valid date.',
            'end_date.required' => 'The End Date field is required.',
            'end_date.date' => 'The End Date field must be a valid date.',
            'end_date.after_or_equal' => 'The End Date field must be after or equal to the Start Date.',
            'status.required' => 'The Status field is required.',
            'status.in' => 'The Status field must be either 0 or 1.',
            'is_show.required' => 'The Show Coupon field is required.',
            'is_show.in' => 'The Show Coupon field must be either 0 or 1.'
        ];
    }
}
