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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminLoginInfoMail;

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
     * Confirm the registration data.
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'shop_name' => ['required', 'string', 'max:200'],
            'job-url' => ['nullable', 'string'], // Changed from shop_description
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'tel-1' => ['required', 'string'],
            'tel-2' => ['required', 'string'],
            'tel-3' => ['required', 'string'],
            'zip-code-1' => ['required', 'string'],
            'zip-code-2' => ['required', 'string'],
            'city' => ['required', 'string'],
            'building' => ['nullable', 'string'],
            'recipient' => ['required', 'string'],
        ]);

        // Map request inputs to the keys expected by the confirm view
        $data = [
            'email' => $request->email,
            'shop_name' => $request->shop_name,
            'shop_description' => $request->input('job-url'),
            'tel' => $request->input('tel-1') . '-' . $request->input('tel-2') . '-' . $request->input('tel-3'),
            'zip1' => $request->input('zip-code-1'),
            'zip2' => $request->input('zip-code-2'),
            'address' => $request->input('city') . ' ' . $request->input('building'),
            'pic_name' => $request->input('recipient'),
        ];

        if ($this->isMobile($request->header('User-Agent'))) {
            return view('auth.confirm_sp', compact('data'));
        }

        return view('auth.confirm', compact('data'));
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
            'tel' => ['nullable', 'string'],
            'pic_name' => ['nullable', 'string'],
            'zip1' => ['nullable', 'string'],
            'zip2' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ]);

        $password = Str::random(10);

        $user = User::create([
            'name' => $request->pic_name, // Save recipient name as user name
            'email' => $request->email,
            'password' => Hash::make($password),
            'shop_name' => $request->shop_name,
            'shop_description' => $request->shop_description,
            'tel' => $request->tel,
            'zip1' => $request->zip1,
            'zip2' => $request->zip2,
            'address' => $request->address,
            'job_url' => $request->shop_description, // Map shop_description (which contains job-url from confirm) to job_url
            'logined_at' => now(),
        ]);

        event(new Registered($user));

        // Send login info mail
        Mail::to($user->email)->send(new AdminLoginInfoMail($user, $password));

        Auth::login($user);

        return redirect(route('register.thanks'));
    }

    /**
     * Display the registration success view.
     */
    public function thanks(Request $request)
    {
        if ($this->isMobile($request->header('User-Agent'))) {
            return view('auth.thanks_sp');
        }
        return view('auth.thanks');
    }
}
