<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopController extends Controller
{
    /**
     * サイトマップ ID: 1 - TOPページ（求職者向け）
     * サービスのプロモーションを掲載
     */
    public function index()
    {
        return view('index');
    }
}
