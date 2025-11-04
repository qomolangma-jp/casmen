<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * サイトマップ ID: 9 - 応募者一覧
     * 全応募者データを取得して一覧表示
     */
    public function index()
    {
        $entries = Entry::all();
        return view('admin.entry.index', compact('entries'));
    }

    /**
     * サイトマップ ID: 10 - 応募者詳細
     * 指定された応募者の詳細情報を表示
     */
    public function show($id)
    {
        $entry = Entry::findOrFail($id);
        return view('admin.entry.show', compact('entry'));
    }

    /**
     * 応募者専用面接URL表示
     * 指定された応募者の面接URL情報を表示
     */
    public function interview($id)
    {
        $entry = Entry::findOrFail($id);
        return view('admin.entry.interview', compact('entry'));
    }
}
