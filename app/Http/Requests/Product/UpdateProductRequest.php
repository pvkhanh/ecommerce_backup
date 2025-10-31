<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ProductStatus;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép tất cả admin, có thể customize nếu cần phân quyền
    }

    public function rules(): array
    {
        $productId = $this->route('id'); // Lấy ID từ route để check unique slug khi update

        return [
            // 'name' => ['required', 'string', 'max:255'],
            // 'slug' => [
            //     'required',
            //     'string',
            //     'max:255',
            //     Rule::unique('products', 'slug')->ignore($productId)
            'name' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                Rule::unique('products', 'slug')->ignore($this->product), // 👈 Quan trọng
            ],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:' . implode(',', ProductStatus::values())],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'image_ids' => ['nullable', 'array'],
            'image_ids.*' => ['exists:images,id'],
            'primary_image_id' => ['nullable', 'exists:images,id'],
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('image_ids') && is_string($this->image_ids)) {
            $this->merge([
                'image_ids' => array_filter(explode(',', $this->image_ids)),
            ]);
        }
    }
}
