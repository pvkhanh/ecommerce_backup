<?php

namespace App\Http\Requests;

use App\Models\Image;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ImageStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images' => ['required', 'array', 'min:1', 'max:10'],
            'images.*' => [
                'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:5120', // 5MB
            ],
            'type' => ['required', 'string', Rule::in(array_keys(Image::getTypes()))],
            'alt_text' => ['nullable', 'array'],
            'alt_text.*' => ['nullable', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Vui lòng chọn ít nhất 1 ảnh',
            'images.array' => 'Dữ liệu ảnh không hợp lệ',
            'images.max' => 'Chỉ được upload tối đa 10 ảnh',
            'images.*.required' => 'File ảnh không hợp lệ',
            'images.*.image' => 'File phải là ảnh',
            'images.*.mimes' => 'Ảnh phải có định dạng: jpeg, jpg, png, gif, webp',
            'images.*.max' => 'Kích thước ảnh không được vượt quá 5MB',
            'type.required' => 'Vui lòng chọn loại ảnh',
            'type.in' => 'Loại ảnh không hợp lệ',
            'alt_text.*.max' => 'Alt text không được vượt quá 255 ký tự',
        ];
    }
}