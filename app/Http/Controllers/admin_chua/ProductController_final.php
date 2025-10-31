<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\CategoryableRepository;
use App\Repositories\Eloquent\ImageRepository;
use App\Repositories\Eloquent\ImageableRepository;
use App\Repositories\Eloquent\StockItemRepository;

class ProductController extends Controller
{
    protected ProductRepository $products;
    protected CategoryRepository $categories;
    protected CategoryableRepository $categoryables;
    protected ImageRepository $images;
    protected ImageableRepository $imageables;
    protected StockItemRepository $stockItems;

    public function __construct(
        ProductRepository $products,
        CategoryRepository $categories,
        CategoryableRepository $categoryables,
        ImageRepository $images,
        ImageableRepository $imageables,
        StockItemRepository $stockItems
    ) {
        $this->products = $products;
        $this->categories = $categories;
        $this->categoryables = $categoryables;
        $this->images = $images;
        $this->imageables = $imageables;
        $this->stockItems = $stockItems;
    }

    // --- DANH SÁCH ---
    public function index(Request $request)
    {
        $products = $this->products->searchPaginated($request->get('search'), 15);
        return view('admin.products.index', compact('products'));
    }

    // --- FORM TẠO ---
    public function create()
    {
        $categories = $this->categories->getTree();
        return view('admin.products.form', compact('categories'));
    }

    // --- LƯU TẠO ---
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            // 'sku' => 'nullable|string|max:50|unique:products,sku',
            'sku' => 'required|string|max:255|unique:products,sku,' . ($product->id ?? 'NULL'),
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'main_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'stock.*.location' => 'required|string|max:100',
            'stock.*.quantity' => 'required|integer|min:0',
        ]);

        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(Str::random(6));
        }

        DB::transaction(function () use ($data, $request) {
            $product = $this->products->create([
                'name' => $data['name'],
                'sku' => $data['sku'],
                'price' => $data['price'],
                'description' => $data['description'] ?? null,
            ]);

            // --- Gắn danh mục ---
            $this->categoryables->create([
                'categoryable_type' => 'App\Models\Product',
                'categoryable_id' => $product->id,
                'category_id' => $data['category_id'],
            ]);

            // --- Ảnh chính ---
            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')->store('products', 'public');
                $image = $this->images->create(['path' => $path, 'type' => 'main', 'is_active' => true]);
                $this->imageables->create([
                    'imageable_type' => 'App\Models\Product',
                    'imageable_id' => $product->id,
                    'image_id' => $image->id,
                    'is_main' => true,
                    'position' => 1
                ]);
            }

            // --- Ảnh gallery ---
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $image = $this->images->create(['path' => $path, 'type' => 'gallery', 'is_active' => true]);
                    $this->imageables->create([
                        'imageable_type' => 'App\Models\Product',
                        'imageable_id' => $product->id,
                        'image_id' => $image->id,
                        'is_main' => false,
                        'position' => $i + 1
                    ]);
                }
            }

            // --- Tồn kho ---
            if ($request->has('stock')) {
                foreach ($request->stock as $stock) {
                    $this->stockItems->create([
                        'variant_id' => null,
                        'product_id' => $product->id,
                        'location' => $stock['location'],
                        'quantity' => $stock['quantity']
                    ]);
                }
            }
        });

        return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công');
    }

    // --- FORM SỬA ---
    public function edit(int $id)
    {
        $product = $this->products->findOrFail($id);
        $categories = $this->categories->getTree();
        return view('admin.products.form', compact('product', 'categories'));
    }

    // --- CẬP NHẬT ---
    public function update(Request $request, int $id)
    {
        $product = $this->products->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            // 'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'sku' => 'required|string|max:255|unique:products,sku,' . ($product->id ?? 'NULL'),
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'main_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'stock.*.location' => 'required|string|max:100',
            'stock.*.quantity' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($data, $request, $product) {

            $this->products->update($product->id, [
                'name' => $data['name'],
                'sku' => $data['sku'],
                'price' => $data['price'],
                'description' => $data['description'] ?? null,
            ]);

            // --- Update danh mục ---
            $cat = $this->categoryables->ofProduct($product->id)->first();
            if ($cat) {
                $this->categoryables->update($cat->id, ['category_id' => $data['category_id']]);
            } else {
                $this->categoryables->create([
                    'categoryable_type' => 'App\Models\Product',
                    'categoryable_id' => $product->id,
                    'category_id' => $data['category_id']
                ]);
            }

            // --- Ảnh chính ---
            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')->store('products', 'public');
                $image = $this->images->create(['path' => $path, 'type' => 'main', 'is_active' => true]);
                $this->imageables->forModel('App\Models\Product', $product->id)->where('is_main', true)->delete();
                $this->imageables->create([
                    'imageable_type' => 'App\Models\Product',
                    'imageable_id' => $product->id,
                    'image_id' => $image->id,
                    'is_main' => true,
                    'position' => 1
                ]);
            }

            // --- Ảnh gallery ---
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $i => $file) {
                    $path = $file->store('products', 'public');
                    $image = $this->images->create(['path' => $path, 'type' => 'gallery', 'is_active' => true]);
                    $this->imageables->create([
                        'imageable_type' => 'App\Models\Product',
                        'imageable_id' => $product->id,
                        'image_id' => $image->id,
                        'is_main' => false,
                        'position' => $i + 1
                    ]);
                }
            }

            // --- Tồn kho ---
            $this->stockItems->forProduct($product->id)->each(function ($stock) {
                $stock->delete();
            });
            if ($request->has('stock')) {
                foreach ($request->stock as $stock) {
                    $this->stockItems->create([
                        'variant_id' => null,
                        'product_id' => $product->id,
                        'location' => $stock['location'],
                        'quantity' => $stock['quantity']
                    ]);
                }
            }

        });

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công');
    }

    // --- XÓA ---
    public function destroy(int $id)
    {
        $product = $this->products->findOrFail($id);

        DB::transaction(function () use ($product) {
            $this->stockItems->forProduct($product->id)->each(fn($s) => $s->delete());
            $this->imageables->forModel('App\Models\Product', $product->id)->each(fn($img) => $img->delete());
            $this->categoryables->ofProduct($product->id)->each(fn($cat) => $cat->delete());
            $this->products->delete($product->id);
        });

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công');
    }

    // --- SHOW ---
    public function show(int $id)
    {
        $product = $this->products->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }
}