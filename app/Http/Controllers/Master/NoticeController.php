<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    /**
     * サイトマップ ID: 18 - お知らせ一覧（マスター）
     */
    public function index()
    {
        try {
            $notices = DB::table('notice')
                ->leftJoin('mst_categories', 'notice.category_id', '=', 'mst_categories.category_id')
                ->select('notice.*', 'mst_categories.category_name')
                ->orderBy('notice.created_at', 'desc')
                ->paginate(10);

            return view('master.notice.index', compact('notices'));
        } catch (\Exception $e) {
            Log::error('Master Notice一覧取得エラー: ' . $e->getMessage());
            return redirect()->back()->with('error', 'お知らせの取得に失敗しました。');
        }
    }

    /**
     * サイトマップ ID: 17 - お知らせ作成
     */
    public function create()
    {
        try {
            $categories = DB::table('mst_categories')->get();
            return view('master.notice.create', compact('categories'));
        } catch (\Exception $e) {
            Log::error('Master Notice作成画面エラー: ' . $e->getMessage());
            return redirect()->back()->with('error', '作成画面の表示に失敗しました。');
        }
    }

    /**
     * お知らせ保存処理
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'nullable|exists:mst_categories,category_id',
            ]);

            DB::table('notice')->insert([
                'title' => $validated['title'],
                'text' => $validated['content'],
                'category_id' => $validated['category_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('master.notice.index')
                ->with('success', 'お知らせを作成しました。');

        } catch (\Exception $e) {
            Log::error('Master Notice作成エラー: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'お知らせの作成に失敗しました。');
        }
    }

    /**
     * サイトマップ ID: 19 - お知らせ編集
     */
    public function edit($id)
    {
        try {
            $notice = DB::table('notice')->where('notice_id', $id)->first();
            if (!$notice) {
                abort(404);
            }

            $categories = DB::table('mst_categories')->get();
            return view('master.notice.edit', compact('notice', 'categories'));
        } catch (\Exception $e) {
            Log::error('Master Notice編集画面エラー: ' . $e->getMessage());
            return redirect()->back()->with('error', '編集画面の表示に失敗しました。');
        }
    }

    /**
     * お知らせ更新処理
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'nullable|exists:mst_categories,category_id',
            ]);

            $updated = DB::table('notice')
                ->where('notice_id', $id)
                ->update([
                    'title' => $validated['title'],
                    'text' => $validated['content'],
                    'category_id' => $validated['category_id'],
                    'updated_at' => now(),
                ]);

            if (!$updated) {
                abort(404);
            }

            return redirect()
                ->route('master.notice.index')
                ->with('success', 'お知らせを更新しました。');

        } catch (\Exception $e) {
            Log::error('Master Notice更新エラー: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'お知らせの更新に失敗しました。');
        }
    }

    /**
     * お知らせ削除処理
     */
    public function destroy($id)
    {
        try {
            $deleted = DB::table('notice')->where('notice_id', $id)->delete();

            if (!$deleted) {
                abort(404);
            }

            return redirect()
                ->route('master.notice.index')
                ->with('success', 'お知らせを削除しました。');

        } catch (\Exception $e) {
            Log::error('Master Notice削除エラー: ' . $e->getMessage());
            return redirect()
                ->back()
                ->with('error', 'お知らせの削除に失敗しました。');
        }
    }
}
