<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * サイトマップ ID: 2 - らくらくセルフ面接（求職者向け）
     * らくらくセルフ面接機能
     */
    public function record()
    {
        return view('record.index');
    }
}
