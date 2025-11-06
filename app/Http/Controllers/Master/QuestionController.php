<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::with('category');

        // カテゴリーフィルター
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 検索機能
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('q', 'like', "%{$search}%")
                  ->orWhere('memo', 'like', "%{$search}%");
            });
        }

        $questions = $query->orderBy('order', 'asc')
                          ->orderBy('question_id', 'asc')
                          ->paginate(15);

        $categories = Category::orderBy('category_order', 'asc')->get();

        return view('master.question.index', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('category_order', 'asc')->get();
        return view('master.question.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:mst_categories,category_id',
            'q' => 'required|string|max:500',
            'memo' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0'
        ]);

        Question::create($request->all());

        return redirect()->route('master.question.index')
                        ->with('success', '質問が正常に作成されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        $question->load('category');
        return view('master.question.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $categories = Category::orderBy('category_order', 'asc')->get();
        return view('master.question.edit', compact('question', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'category_id' => 'required|exists:mst_categories,category_id',
            'q' => 'required|string|max:500',
            'memo' => 'nullable|string|max:1000',
            'order' => 'required|integer|min:0'
        ]);

        $question->update($request->all());

        return redirect()->route('master.question.index')
                        ->with('success', '質問が正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('master.question.index')
                        ->with('success', '質問が正常に削除されました。');
    }
}
