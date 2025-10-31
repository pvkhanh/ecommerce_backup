<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Imageable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $query = Image::query()->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('path', 'like', '%' . $request->search . '%')
                  ->orWhere('alt_text', 'like', '%' . $request->search . '%');
            });
        }

        $images = $query->paginate(20);

        return view('admin.images.index', compact('images'));
    }

    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'type' => 'required|string|max:50',
            'alt_text.*' => 'nullable|string|max:255',
        ]);

        $uploadedImages = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('images', $filename, 'public');

                $image = Image::create([
                    'path' => $path,
                    'type' => $request->type,
                    'alt_text' => $request->alt_text[$index] ?? null,
                    'is_active' => true,
                ]);

                $uploadedImages[] = $image;
            }
        }

        return redirect()->route('admin.images.index')
            ->with('success', count($uploadedImages) . ' ảnh đã được tải lên thành công!');
    }

    public function edit(Image $image)
    {
        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'alt_text' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $data = [
            'type' => $request->type,
            'alt_text' => $request->alt_text,
            'is_active' => $request->has('is_active'),
        ];

        // Replace image if new file uploaded
        if ($request->hasFile('image')) {
            // Delete old image
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('images', $filename, 'public');
            $data['path'] = $path;
        }

        $image->update($data);

        return redirect()->route('admin.images.index')
            ->with('success', 'Cập nhật ảnh thành công!');
    }

    public function destroy(Image $image)
    {
        // Delete file from storage
        if ($image->path && Storage::disk('public')->exists($image->path)) {
            Storage::disk('public')->delete($image->path);
        }

        // Delete relationships
        Imageable::where('image_id', $image->id)->delete();

        $image->delete();

        return redirect()->route('admin.images.index')
            ->with('success', 'Xóa ảnh thành công!');
    }

    // API for product image selection
    public function apiList(Request $request)
    {
        $query = Image::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('alt_text', 'like', '%' . $request->search . '%');
        }

        $images = $query->where('is_active', true)
            ->latest()
            ->paginate(12);

        return response()->json($images);
    }
}
