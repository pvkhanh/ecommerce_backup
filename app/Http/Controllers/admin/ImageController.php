<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\Imageable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as InterventionImage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $query = Image::query()->latest();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by active status
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('path', 'like', '%' . $request->search . '%')
                    ->orWhere('alt_text', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $images = $query->paginate(24)->withQueryString();

        // Get statistics
        $stats = [
            'total' => Image::count(),
            'active' => Image::where('is_active', true)->count(),
            'by_type' => Image::select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->pluck('count', 'type')
                ->toArray(),
        ];

        return view('admin.images.index', compact('images', 'stats'));
    }

    public function create()
    {
        return view('admin.images.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB
            'type' => 'required|string|max:50',
            'alt_text.*' => 'nullable|string|max:255',
            'optimize' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $uploadedImages = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

                    // Optimize image if requested
                    if ($request->has('optimize')) {
                        $image = InterventionImage::make($file);

                        // Resize if too large
                        if ($image->width() > 1920 || $image->height() > 1920) {
                            $image->resize(1920, 1920, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });
                        }

                        // Optimize quality
                        $image->save(storage_path('app/public/images/' . $filename), 85);
                        $path = 'images/' . $filename;
                    } else {
                        $path = $file->storeAs('images', $filename, 'public');
                    }

                    $imageModel = Image::create([
                        'path' => $path,
                        'type' => $request->type,
                        'alt_text' => $request->alt_text[$index] ?? null,
                        'is_active' => true,
                    ]);

                    $uploadedImages[] = $imageModel;
                }
            }

            DB::commit();

            return redirect()->route('admin.images.index')
                ->with('success', count($uploadedImages) . ' ảnh đã được tải lên thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function edit(Image $image)
    {
        // Load relationships to show usage
        $image->load('imageables.imageable');

        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'alt_text' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'type' => $request->type,
                'alt_text' => $request->alt_text,
                // ✅ Sửa dòng này
                'is_active' => $request->boolean('is_active'),
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

            DB::commit();

            return redirect()->route('admin.images.index')
                ->with('success', 'Cập nhật ảnh thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }


    public function destroy(Image $image)
    {
        DB::beginTransaction();
        try {
            // Check if image is being used
            $usageCount = Imageable::where('image_id', $image->id)->count();

            if ($usageCount > 0) {
                return back()->with(
                    'error',
                    "Không thể xóa ảnh này vì đang được sử dụng ở {$usageCount} nơi. Vui lòng gỡ liên kết trước."
                );
            }

            // Delete file from storage
            if ($image->path && Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }

            // Delete record
            $image->delete();

            DB::commit();

            return redirect()->route('admin.images.index')
                ->with('success', 'Xóa ảnh thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * API for product image selection
     */
    // public function apiList(Request $request)
    // {
    //     $query = Image::query();

    //     if ($request->filled('type')) {
    //         $query->where('type', $request->type);
    //     }

    //     if ($request->filled('search')) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('alt_text', 'like', '%' . $request->search . '%')
    //                 ->orWhere('path', 'like', '%' . $request->search . '%');
    //         });
    //     }

    //     $images = $query->where('is_active', true)
    //         ->latest()
    //         ->paginate($request->get('per_page', 12));
    //     // ✅ Thêm URL ảnh cho mỗi bản ghi

    //     return response()->json($images);
    // }

    public function apiList(Request $request)
    {
        $query = Image::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('path', 'like', "%{$search}%")
                    ->orWhere('alt_text', 'like', "%{$search}%");
            });
        }

        $images = $query->latest()->paginate($request->input('per_page', 12));

        // Thêm URL đầy đủ cho frontend
        $images->getCollection()->transform(function ($image) {
            $image->url = asset('storage/' . $image->path);
            $image->filename = basename($image->path);
            return $image;
        });

        return response()->json($images);
    }

    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,change_type',
            'image_ids' => 'required|array',
            'image_ids.*' => 'exists:images,id',
            'new_type' => 'required_if:action,change_type',
        ]);

        DB::beginTransaction();
        try {
            $images = Image::whereIn('id', $request->image_ids)->get();
            $count = 0;

            foreach ($images as $image) {
                switch ($request->action) {
                    case 'delete':
                        // Check usage
                        $usageCount = Imageable::where('image_id', $image->id)->count();
                        if ($usageCount == 0) {
                            if ($image->path && Storage::disk('public')->exists($image->path)) {
                                Storage::disk('public')->delete($image->path);
                            }
                            $image->delete();
                            $count++;
                        }
                        break;

                    case 'activate':
                        $image->update(['is_active' => true]);
                        $count++;
                        break;

                    case 'deactivate':
                        $image->update(['is_active' => false]);
                        $count++;
                        break;

                    case 'change_type':
                        $image->update(['type' => $request->new_type]);
                        $count++;
                        break;
                }
            }

            DB::commit();

            $actionNames = [
                'delete' => 'xóa',
                'activate' => 'kích hoạt',
                'deactivate' => 'vô hiệu hóa',
                'change_type' => 'thay đổi loại',
            ];

            return back()->with(
                'success',
                "Đã {$actionNames[$request->action]} {$count} ảnh thành công!"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show image details and usage
     */
    public function show(Image $image)
    {
        $image->load(['imageables.imageable']);

        // Group usage by type
        $usageByType = $image->imageables->groupBy('imageable_type');

        return view('admin.images.show', compact('image', 'usageByType'));
    }

    //Thêm ngày 29
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'type' => 'required|string|max:50'
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('images', $filename, 'public');

        $image = Image::create([
            'path' => $path,
            'type' => $request->type,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'image' => [
                'id' => $image->id,
                'url' => asset('storage/' . $image->path)
            ]
        ]);
    }

}