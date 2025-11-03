<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Update user email if changed
        if (isset($validated['email'])) {
            $user->fill(['email' => $validated['email']]);
            
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            
            $user->save();
        }

        // Update or create shop information
        $shop = $user->shop;
        if ($shop) {
            $shop->update([
                'shop_name' => $validated['shop_name'],
                'shop_description' => $validated['shop_description'],
            ]);
        } else {
            Shop::create([
                'user_id' => $user->id,
                'shop_name' => $validated['shop_name'],
                'shop_description' => $validated['shop_description'],
                'login_date' => now(),
            ]);
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
