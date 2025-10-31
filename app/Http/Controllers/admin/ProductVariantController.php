<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductVariantController extends Controller
{
    /**
     * Display variants for a product
     */
    public function index(Product $product)
    {
        $variants = $product->variants()
            ->with('stockItems')
            ->get();

        return view('admin.products.variants.index', compact('product', 'variants'));
    }

    /**
     * Show form to create variant
     */
    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }

    /**
     * Store new variant
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:product_variants,sku',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_location' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create variant
            $variant = $product->variants()->create([
                'name' => $request->name,
                'sku' => $request->sku,
                'price' => $request->price,
            ]);

            // Create stock item if quantity provided
            if ($request->filled('stock_quantity')) {
                StockItem::create([
                    'variant_id' => $variant->id,
                    'location' => $request->stock_location ?? 'default',
                    'quantity' => $request->stock_quantity,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.variants.index', $product)
                ->with('success', 'Tạo biến thể thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit variant
     */
    public function edit(Product $product, ProductVariant $variant)
    {
        $variant->load('stockItems');
        return view('admin.products.variants.edit', compact('product', 'variant'));
    }

    /**
     * Update variant
     */
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:product_variants,sku,' . $variant->id,
            'price' => 'required|numeric|min:0',
        ]);

        $variant->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
        ]);

        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Cập nhật biến thể thành công!');
    }

    /**
     * Delete variant
     */
    public function destroy(Product $product, ProductVariant $variant)
    {
        try {
            // Delete stock items first
            $variant->stockItems()->delete();

            // Delete variant
            $variant->delete();

            return redirect()
                ->route('admin.products.variants.index', $product)
                ->with('success', 'Xóa biến thể thành công!');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Manage stock for variant
     */
    public function stock(Product $product, ProductVariant $variant)
    {
        $variant->load('stockItems');
        return view('admin.products.variants.stock', compact('product', 'variant'));
    }

    /**
     * Update stock
     */
    public function updateStock(Request $request, Product $product, ProductVariant $variant)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'action' => 'required|in:set,increase,decrease',
        ]);

        DB::beginTransaction();
        try {
            $stockItem = StockItem::firstOrCreate(
                [
                    'variant_id' => $variant->id,
                    'location' => $request->location,
                ],
                ['quantity' => 0]
            );

            switch ($request->action) {
                case 'set':
                    $stockItem->quantity = $request->quantity;
                    break;
                case 'increase':
                    $stockItem->quantity += $request->quantity;
                    break;
                case 'decrease':
                    $stockItem->quantity = max(0, $stockItem->quantity - $request->quantity);
                    break;
            }

            $stockItem->save();

            DB::commit();

            return back()->with('success', 'Cập nhật tồn kho thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Bulk create variants
     */
    public function bulkCreate(Request $request, Product $product)
    {
        $request->validate([
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string|max:100|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.quantity' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->variants as $variantData) {
                $variant = $product->variants()->create([
                    'name' => $variantData['name'],
                    'sku' => $variantData['sku'],
                    'price' => $variantData['price'],
                ]);

                if (!empty($variantData['quantity'])) {
                    StockItem::create([
                        'variant_id' => $variant->id,
                        'location' => 'default',
                        'quantity' => $variantData['quantity'],
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('admin.products.variants.index', $product)
                ->with('success', 'Tạo ' . count($request->variants) . ' biến thể thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    //Thêm ngày 30/10 tạo sku tự động
    public function storeMany(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variants.*.name' => 'required|string|max:255',
            'variants.*.sku' => 'required|string|max:255|unique:product_variants,sku',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
        ]);

        foreach ($validated['variants'] as $data) {
            $product->variants()->create($data);
        }

        return redirect()->route('admin.products.variants.index', $product)
            ->with('success', 'Đã tạo biến thể tự động thành công!');
    }
    // public function checkSKU(Request $request)
    // {
    //     $sku = $request->query('sku');
    //     $productId = $request->query('product_id');

    //     $exists = \App\Models\ProductVariant::where('sku', $sku)
    //         ->whereHas('product', function ($q) use ($productId) {
    //             $q->where('id', $productId);
    //         })
    //         ->exists();

    //     return response()->json(['exists' => $exists]);
    // }

    // public function suggestSKU(Request $request)
    // {
    //     $sku = $request->query('sku');
    //     $productId = $request->query('product_id');

    //     $base = $sku;
    //     $counter = 2;

    //     while (\App\Models\ProductVariant::where('sku', $base)->exists()) {
    //         $base = preg_replace('/-\d+$/', '', $sku) . '-' . $counter;
    //         $counter++;
    //     }

    //     return response()->json(['suggested' => $base]);
    // }
    
    //Thêm ngày 30/10
    public function checkSku(Product $product, Request $request)
    {
        $sku = $request->query('sku');
        $exists = $product->variants()->where('sku', $sku)->exists();

        return response()->json(['exists' => $exists]);
    }
    // public function showBulkCreate(Product $product)
    // {
    //     return view('admin.products.variants.bulk_create', compact('product'));
    // }

    // public function bulkCreate(Request $request, Product $product)
    // {
    //     $validated = $request->validate([
    //         'variants' => 'required|array|min:1',
    //         'variants.*.name' => 'required|string|max:255',
    //         'variants.*.sku' => 'required|string|max:255|distinct',
    //         'variants.*.price' => 'required|numeric|min:0',
    //     ]);

    //     $created = [];

    //     foreach ($validated['variants'] as $variantData) {
    //         // Kiểm tra SKU trùng
    //         $exists = $product->variants()->where('sku', $variantData['sku'])->exists();
    //         if ($exists) continue;

    //         $created[] = $product->variants()->create([
    //             'name'  => $variantData['name'],
    //             'sku'   => $variantData['sku'],
    //             'price' => $variantData['price'],
    //         ]);
    //     }

    //     return redirect()
    //         ->route('admin.products.variants.index', $product)
    //         ->with('success', count($created) . ' biến thể đã được thêm thành công!');
    // }

}