<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Phân quyền theo middleware nếu cần
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $this->route('product'),
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'main_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'stock' => 'nullable|array',
            'stock.*.location' => 'required_with:stock|string|max:100',
            'stock.*.quantity' => 'required_with:stock|integer|min:0',
        ];
    }
}