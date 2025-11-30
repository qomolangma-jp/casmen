<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Notice;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * サイトマップ ID: 5 - ダッシュボード（店舗管理画面TOP）
     * 店舗向け管理画面のダッシュボード
     */
    public function index()
    {
        // 評価待ちの応募者数を取得
        $waitingCount = Entry::where('status', 'completed')
            ->whereNull('decision_at')
            ->count();

        // 最新のお知らせを5件取得
        $notices = Notice::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('waitingCount', 'notices'));
    }
}