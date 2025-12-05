<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    /**
     * サイトマップ ID: 1 - TOPページ（求職者向け）
     * サービスのプロモーションを掲載
     */
    public function index(Request $request)
    {
        $userAgent = $request->header('User-Agent');

        if ($this->isMobile($userAgent)) {
            return view('top.index_sp');
        }

        return view('top.index');
    }

    /**
     * モバイルデバイス判定
     */
    private function isMobile($userAgent)
    {
        // iPhone, iPod, Android Mobile をモバイルと判定
        // iPad や Android Tablet はPCビューを表示
        return preg_match('/(iPhone|iPod|Android.*Mobile)/i', $userAgent);
    }
}
