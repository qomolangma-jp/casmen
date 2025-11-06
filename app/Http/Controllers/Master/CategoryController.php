<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // グループフィルター
        if ($request->filled('category_group')) {
            $query->where('category_group', $request->category_group);
        }

        // 検索機能
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('category_name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%")
                  ->orWhere('category_group', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('category_order', 'asc')
                          ->orderBy('category_id', 'asc')
                          ->paginate(15);

        // グループ一覧取得
        $groups = Category::select('category_group')
                         ->whereNotNull('category_group')
                         ->distinct()
                         ->orderBy('category_group')
                         ->pluck('category_group');

        $totalCategories = Category::count();

        return view('master.category.index', compact('categories', 'groups', 'totalCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 既存のグループ一覧を取得
        $existingGroups = Category::select('category_group')
                                ->whereNotNull('category_group')
                                ->distinct()
                                ->orderBy('category_group')
                                ->pluck('category_group');

        return view('master.category.create', compact('existingGroups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|max:200|unique:mst_categories,slug',
            'category_name' => 'required|string|max:200',
            'category_group' => 'nullable|string|max:200',
            'category_order' => 'required|integer|min:0'
        ]);

        Category::create($request->all());

        return redirect()->route('master.category.index')
                        ->with('success', 'カテゴリーが正常に作成されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        // 関連する質問も取得
        $category->load('questions');
        return view('master.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        // 既存のグループ一覧を取得
        $existingGroups = Category::select('category_group')
                                ->whereNotNull('category_group')
                                ->distinct()
                                ->orderBy('category_group')
                                ->pluck('category_group');

        return view('master.category.edit', compact('category', 'existingGroups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'slug' => [
                'required',
                'string',
                'max:200',
                Rule::unique('mst_categories', 'slug')->ignore($category->category_id, 'category_id')
            ],
            'category_name' => 'required|string|max:200',
            'category_group' => 'nullable|string|max:200',
            'category_order' => 'required|integer|min:0'
        ]);

        $category->update($request->all());

        return redirect()->route('master.category.index')
                        ->with('success', 'カテゴリーが正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // 関連する質問があるかチェック
        if ($category->questions()->count() > 0) {
            return redirect()->route('master.category.index')
                           ->with('error', 'このカテゴリーには質問が関連付けられているため削除できません。');
        }

        $category->delete();

        return redirect()->route('master.category.index')
                        ->with('success', 'カテゴリーが正常に削除されました。');
    }
}
