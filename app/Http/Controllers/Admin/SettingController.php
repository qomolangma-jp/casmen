<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('admin.setting.index', compact('user'));
    }

    public function update(Request $request)
    {
        return redirect()->route('admin.setting.index');
    }
}
