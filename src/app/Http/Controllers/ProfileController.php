<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function showEditForm()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|regex:/^[0-9]{3}-[0-9]{4}$/',
            'prefecture' => 'required',
            'city' => 'required',
            'street' => 'required',
            'building' => 'nullable'
        ]);

        // プロフィールと住所情報の更新処理
        return redirect()->back()->with('success', '更新が完了しました');
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.address-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'postal_code' => 'required|regex:/^[0-9]{3}-[0-9]{4}$/',
            'address' => 'required',
            'building' => 'nullable'
        ]);

        $user = Auth::user();
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building
            ]
        );

        return redirect()->back()->with('success', '住所を更新しました');
    }
}
