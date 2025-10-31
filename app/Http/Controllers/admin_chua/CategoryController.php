<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
    }

    public function index(Request $request)
    {
        $keyword = $request->query('search', null);

        if (method_exists($this->categoryRepository, 'searchPaginated')) {
            $categories = $this->categoryRepository->searchPaginated($keyword, 10);
        } else {
            $query = $this->categoryRepository->newQuery();
            if ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            }
            $categories = $this->categoryRepository->paginateQuery($query, 10);
        }

        return view('admin.categories.index', compact('categories', 'keyword'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|unique:categories,slug',
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $this->categoryRepository->create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit($id)
    {
        $category = $this->categoryRepository->findOrFail((int) $id);

        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'is_active' => 'sometimes|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $this->categoryRepository->update((int) $id, $validated);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $this->categoryRepository->delete((int) $id);

        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }
}
