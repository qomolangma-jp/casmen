<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\User;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Entry::with('user')->whereNotNull('interview_uuid');

        // 検索機能
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('interview_uuid', 'like', "%{$search}%");
            });
        }

        // ステータスフィルター（expires_atカラムがないため、created_atから30日後で判定）
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'active') {
                $query->where('created_at', '>', now()->subDays(30))
                      ->whereNull('video_path');
            } elseif ($status === 'expired') {
                $query->where('created_at', '<=', now()->subDays(30))
                      ->whereNull('video_path');
            } elseif ($status === 'completed') {
                $query->whereNotNull('video_path');
            }
        }

        // 店舗フィルター
        if ($request->filled('shop')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('shop_name', 'like', "%{$request->shop}%");
            });
        }

        // ページネーション
        $links = $query->orderBy('created_at', 'desc')->paginate(15);

        // 統計データ（expires_atカラムがないため、created_atから30日後で計算）
        $totalLinks = Entry::whereNotNull('interview_uuid')->count();
        $activeLinks = Entry::whereNotNull('interview_uuid')
            ->where('created_at', '>', now()->subDays(30))
            ->whereNull('video_path')
            ->count();
        $completedLinks = Entry::whereNotNull('interview_uuid')->whereNotNull('video_path')->count();
        $expiredLinks = Entry::whereNotNull('interview_uuid')
            ->where('created_at', '<=', now()->subDays(30))
            ->whereNull('video_path')
            ->count();

        return view('master.link.index', compact(
            'links',
            'totalLinks',
            'activeLinks',
            'completedLinks',
            'expiredLinks'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $link = Entry::with('user')->whereNotNull('interview_uuid')->findOrFail($id);

        // URLの詳細情報
        $link->interview_url = url("/record?token={$link->interview_uuid}");

        return view('master.link.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
