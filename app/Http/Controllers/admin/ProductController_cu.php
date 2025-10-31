<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use App\Models\Imageable;
use App\Enums\ProductStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // ================== INDEX ==================
    public function index(Request $request)
    {
        $query = Product::with(['categories', 'variants'])
            ->withCount('reviews')
            ->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category_id);
            });
        }

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $products = $query->paginate(15);

        // ✅ Lấy danh mục cha, tránh lỗi non-static
        $categories = Category::whereNull('parent_id')
            ->orderBy('position')
            ->get();

        $statuses = ProductStatus::cases();

        return view('admin.products.index', compact('products', 'categories', 'statuses'));
    }

    // ================== CREATE ==================
    public function create()
    {
        // ✅ Lấy tất cả danh mục (nếu có status thì lọc, không thì thôi)
        $categories = Category::orderBy('position')->get();
        $statuses = ProductStatus::cases();

        return view('admin.products.create', compact('categories', 'statuses'));
    }

    // ================== STORE ==================
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'image_ids' => 'nullable|array',
            'image_ids.*' => 'exists:images,id',
            'primary_image_id' => 'nullable|exists:images,id',
        ]);

        DB::beginTransaction();
        try {
            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            // Attach categories
            if ($request->filled('category_ids')) {
                $product->categories()->attach($request->category_ids);
            }

            // Attach images
            if ($request->filled('image_ids')) {
                foreach ($request->image_ids as $index => $imageId) {
                    Imageable::create([
                        'image_id' => $imageId,
                        'imageable_id' => $product->id,
                        'imageable_type' => Product::class,
                        'is_main' => ($imageId == $request->primary_image_id),
                        'position' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Tạo sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // ================== SHOW ==================
    public function show(Product $product)
    {
        $product->load([
            'categories',
            'variants.stockItems',
            'reviews.user',
            'images'
        ]);

        return view('admin.products.show', compact('product'));
    }

    // ================== EDIT ==================
    public function edit(Product $product)
    {
        $product->load(['categories', 'images']);

        $categories = Category::orderBy('position')->get();
        $statuses = ProductStatus::cases();

        $selectedImageIds = $product->images->pluck('id')->toArray();
        $primaryImage = $product->images->where('pivot.is_main', true)->first();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'statuses',
            'selectedImageIds',
            'primaryImage'
        ));
    }

    // ================== UPDATE ==================
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'image_ids' => 'nullable|array',
            'image_ids.*' => 'exists:images,id',
            'primary_image_id' => 'nullable|exists:images,id',
        ]);

        DB::beginTransaction();
        try {
            $product->update([
                'name' => $request->name,
                'slug' => $request->slug ?: Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'status' => $request->status,
            ]);

            // Sync categories
            $product->categories()->sync($request->category_ids ?? []);

            // Sync images
            Imageable::where('imageable_type', Product::class)
                ->where('imageable_id', $product->id)
                ->delete();

            if ($request->filled('image_ids')) {
                foreach ($request->image_ids as $index => $imageId) {
                    Imageable::create([
                        'image_id' => $imageId,
                        'imageable_id' => $product->id,
                        'imageable_type' => Product::class,
                        'is_main' => ($imageId == $request->primary_image_id),
                        'position' => $index + 1,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Cập nhật sản phẩm thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // ================== DESTROY ==================
    public function destroy(Product $product)
    {
        try {
            Imageable::where('imageable_type', Product::class)
                ->where('imageable_id', $product->id)
                ->delete();

            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Xóa sản phẩm thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
