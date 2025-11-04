<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * サイトマップ ID: 5 - ダッシュボード（店舗管理画面TOP）
     * 店舗向け管理画面のダッシュボード
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
