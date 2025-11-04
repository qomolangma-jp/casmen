<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterTopController extends Controller
{
    /**
     * サイトマップ ID: 12 - マスターTOP
     * マスター管理画面のダッシュボード
     */
    public function index()
    {
        return view('master.dashboard');
    }
}
