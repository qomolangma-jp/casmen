<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $userAgent = $request->header('User-Agent');

        if ($this->isMobile($userAgent)) {
            return view('auth.register_sp');
        }

        return view('auth.register');
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

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'shop_name' => ['required', 'string', 'max:200'],
            'shop_description' => ['nullable', 'string'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'shop_name' => $request->shop_name,
            'shop_description' => $request->shop_description,
            'logined_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('admin.dashboard'));
    }
}
