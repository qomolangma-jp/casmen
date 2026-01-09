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

    /**
     * CKEditorからの画像アップロード処理
     */
    public function uploadImage(Request $request)
    {
        try {
            Log::info('画像アップロード開始');
            Log::info('リクエストファイル:', ['files' => $request->allFiles()]);

            $request->validate([
                'upload' => 'required|image|max:5120', // 5MB以下
            ]);

            $file = $request->file('upload');
            $filename = time() . '_' . $file->getClientOriginalName();

            Log::info('アップロード情報:', [
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);

            // 'notices'のみを指定（Laravelが自動的にpublicディスクのルート＝storage/app/publicに保存）
            $path = $file->storeAs('notices', $filename, 'public');

            Log::info('保存パス: ' . $path);
            Log::info('実際のファイルパス: ' . storage_path('app/public/' . $path));
            Log::info('ファイル存在確認: ' . (file_exists(storage_path('app/public/' . $path)) ? 'はい' : 'いいえ'));

            // CKEditor 5形式のレスポンス
            return response()->json([
                'uploaded' => true,
                'url' => asset('storage/notices/' . $filename)
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('画像バリデーションエラー: ' . json_encode($e->errors()));
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => $e->errors()['upload'][0] ?? '画像のアップロードに失敗しました。'
                ]
            ], 400);
        } catch (\Exception $e) {
            Log::error('画像アップロードエラー: ' . $e->getMessage());
            Log::error('スタックトレース: ' . $e->getTraceAsString());
            return response()->json([
                'uploaded' => false,
                'error' => [
                    'message' => '画像のアップロードに失敗しました。'
                ]
            ], 500);
        }
    }
}
