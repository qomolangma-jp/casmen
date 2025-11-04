<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    /**
     * サイトマップ ID: 7 - お知らせ一覧
     * 全お知らせデータを取得して一覧表示（ページネーション付き）
     */
    public function index()
    {
        try {
            // 1ページあたり50件でページネーション
            $notices = DB::table('notice')
                ->orderBy('created_at', 'desc')
                ->paginate(50);

            Log::info('Notice total count: ' . $notices->total());
            Log::info('Current page: ' . $notices->currentPage());

        } catch (\Exception $e) {
            Log::error('Notice取得エラー: ' . $e->getMessage());
            // エラー時は空のLengthAwarePaginatorを作成
            $notices = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                3,
                1,
                ['path' => request()->url()]
            );
        }

        return view('admin.notice.index', compact('notices'));
    }    /**
     * サイトマップ ID: 8 - お知らせ詳細
     * 指定されたお知らせの詳細情報を表示
     */
    public function show($id)
    {
        // カテゴリ情報を含めてデータを取得
        $notice = DB::table('notice')
            ->leftJoin('mst_categories', 'notice.category_id', '=', 'mst_categories.category_id')
            ->select('notice.*', 'mst_categories.category_name')
            ->where('notice.notice_id', $id)
            ->first();

        if (!$notice) {
            abort(404);
        }
        return view('admin.notice.show', compact('notice'));
    }
}
