<?php
// <!-- namespace App\Http\Controllers\Admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Repositories\Contracts\ProductRepositoryInterface;
// use App\Models\Category;
// use App\Models\ProductVariant;

// class ProductController extends Controller
// {
// protected ProductRepositoryInterface $products;

// public function __construct(ProductRepositoryInterface $products)
// {
// $this->products = $products;
// }

// public function index(Request $request)
// {
// $query = $this->products->newQuery(); // lấy query builder từ repo

// // 1️⃣ Search theo từ khóa (tên, mã sản phẩm...)
// if ($request->filled('search')) {
// $query->search($request->input('search'));
// }

// // 2️⃣ Filter theo trạng thái
// if ($request->filled('status')) {
// $query->where('status', $request->input('status'));
// }

// // 3️⃣ Filter theo category
// if ($request->filled('category_id')) {
// $categoryId = $request->input('category_id');
// $query->whereHas('categories', function ($q) use ($categoryId) {
// $q->where('categories.id', $categoryId);
// });
// }

// // 4️⃣ Eager load quan hệ
// $query->with(['categories', 'reviews', 'variants.stockItems', 'images']);

// // 5️⃣ Phân trang
// $products = $query->orderBy('created_at', 'desc')
// ->paginate(10)
// ->withQueryString();

// // 6️⃣ Lấy tất cả category cho filter dropdown
// $categories = Category::all();

// // 7️⃣ Tính số sản phẩm còn hàng / hết hàng / tổng biến thể
// $inStockCount = $products->filter(function ($product) {
// return $product->total_stock > 0; // total_stock là attribute trong model
// })->count();

// $outOfStockCount = $products->filter(function ($product) {
// return $product->total_stock === 0;
// })->count();

// $variantsCount = ProductVariant::count();

// // 8️⃣ Truyền tất cả biến sang view
// return view('admin.products.index', compact(
// 'products',
// 'categories',
// 'inStockCount',
// 'outOfStockCount',
// 'variantsCount'
// ));
// }
// } -->


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected ProductRepositoryInterface $products;

    public function __construct(ProductRepositoryInterface $products)
    {
        $this->products = $products;
    }

    public function index(Request $request)
    {
        $query = $this->products->newQuery();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $query->with(['categories', 'reviews', 'variants.stockItems', 'images']);

        $products = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::all();

        // Stats Cards
        $allProducts = $this->products->all(); // lấy tất cả sản phẩm
        $inStockCount = $allProducts->filter(fn($product) => $product->total_stock > 0)->count();
        $outOfStockCount = $allProducts->filter(fn($product) => $product->total_stock === 0)->count();
        $variantsCount = $allProducts->sum(fn($product) => $product->variants->count());

        return view('admin.products.index', compact(
            'products',
            'categories',
            'inStockCount',
            'outOfStockCount',
            'variantsCount'
        ));
    }

    // Hiển thị form tạo sản phẩm mới
    public function create()
    {
        // $categories = Category::all();
        // return view('admin.products.create', compact('categories'));
        // Sinh 5 SKU ngẫu nhiên
        $skus = [];
        for ($i = 0; $i < 5; $i++) {
            $skus[] = 'PRD-' . strtoupper(Str::random(6));
        }

        $categories = Category::all();

        return view('admin.products.create', compact('skus', 'categories'));
    }

    // Lưu sản phẩm mới
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'price' => 'required|numeric',
    //         'category_id' => 'required|exists:categories,id',
    //         'status' => 'required|boolean',
    //     ]);

    //     $product = Product::create($request->all());

    //     if ($request->has('categories')) {
    //         $product->categories()->sync($request->categories);
    //     }

    //     return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            // các validate khác...
        ]);

        // Tự sinh SKU nếu không nhập
        $sku = $request->sku ?: 'PRD-' . strtoupper(Str::random(6));

        $product = Product::create([
            'name' => $request->name,
            'slug' => $request->slug ?: Str::slug($request->name),
            'sku' => $sku,
            'price' => $request->price,
            'cost' => $request->cost,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'status' => $request->status,
            'image' => $request->hasFile('image') ? $request->file('image')->store('products', 'public') : null,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    // Hiển thị chi tiết sản phẩm
    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    // Hiển thị form sửa sản phẩm
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|boolean',
        ]);

        $product->update($request->all());

        if ($request->has('categories')) {
            $product->categories()->sync($request->categories);
        }

        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    // Xóa sản phẩm
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được xóa!');
    }

    // Toggle trạng thái
    public function toggleStatus($id)
    {
        $product = Product::findOrFail($id);
        $product->status = !$product->status;
        $product->save();

        return redirect()->back()->with('success', 'Trạng thái sản phẩm đã được cập nhật!');
    }

    // public function toggleStatus($id)
    // {
    //     $product = $this->products->find($id);
    //     $product->status = !$product->status;
    //     $product->save();
    //     return response()->json(['success' => true]);
    // }

    public function toggleStock($id)
    {
        $product = $this->products->find($id);
        $product->total_stock = $product->total_stock > 0 ? 0 : 10; // default 10 when in stock
        $product->save();
        return response()->json(['success' => true]);
    }
}