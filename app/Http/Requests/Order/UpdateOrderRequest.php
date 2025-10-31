<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OrderStatus;

class UpdateOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:' . implode(',', OrderStatus::values()),
            'admin_note' => 'nullable|string|max:500',
            'shipping_fee' => 'nullable|numeric|min:0|max:10000000',
            'customer_note' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'status' => 'trạng thái',
            'admin_note' => 'ghi chú admin',
            'shipping_fee' => 'phí vận chuyển',
            'customer_note' => 'ghi chú khách hàng',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng',
            'status.in' => 'Trạng thái không hợp lệ',
            'shipping_fee.numeric' => 'Phí vận chuyển phải là số',
            'shipping_fee.min' => 'Phí vận chuyển không được nhỏ hơn 0',
            'shipping_fee.max' => 'Phí vận chuyển không được vượt quá 10,000,000đ',
            'admin_note.max' => 'Ghi chú admin không được vượt quá 500 ký tự',
            'customer_note.max' => 'Ghi chú khách hàng không được vượt quá 500 ký tự',
        ];
    }
}