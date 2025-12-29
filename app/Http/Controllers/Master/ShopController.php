<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereNotNull('shop_name');

        // 検索機能
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('shop_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // 地域フィルター（今回は実装しませんが、必要に応じて追加可能）
        if ($request->filled('region')) {
            // 地域情報がusersテーブルにある場合のみ実装
            // $query->where('prefecture', $request->region);
        }

        // ページネーション
        $shops = $query->orderBy('created_at', 'desc')->paginate(15);

        // 統計データ
        $totalShops = User::whereNotNull('shop_name')->count();
        $thisMonthShops = User::whereNotNull('shop_name')
                             ->whereMonth('created_at', now()->month)
                             ->whereYear('created_at', now()->year)
                             ->count();

        return view('master.shop.index', compact(
            'shops',
            'totalShops',
            'thisMonthShops'
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
        $shop = User::whereNotNull('shop_name')->findOrFail($id);

        // 関連統計データの取得
        $shop->total_interviews = 0; // 面接データがあれば取得
        $shop->active_entries = 0; // エントリーデータがあれば取得

        return view('master.shop.show', compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = User::whereNotNull('shop_name')->findOrFail($id);
        return view('master.shop.edit', compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $shop = User::whereNotNull('shop_name')->findOrFail($id);

        $request->validate([
            'shop_name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $shop->id,
            'tel' => 'nullable|string|max:20',
        ]);

        $shop->update([
            'shop_name' => $request->shop_name,
            'name' => $request->name,
            'email' => $request->email,
            'tel' => $request->tel,
        ]);

        return redirect()->route('master.shop.index')->with('success', '店舗情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $shop = User::whereNotNull('shop_name')->findOrFail($id);
        $shop->delete();

        return redirect()->route('master.shop.index')->with('success', '店舗を削除しました。');
    }
}
