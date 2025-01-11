<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Item;
use App\Models\Purchase;

class ProfileController extends Controller
{
    public function showProfile()
    {
        $user = Auth::user();
        $tab = request('tab', 'sell');

        if ($tab === 'sell') {
            $items = $user->items()->latest()->get();
        } elseif ($tab === 'buy') {
            $items = $user->purchases()->with('item')->latest()->get()->pluck('item');
        } else {
            $items = collect();
        }

        return view('profile.show', compact('user', 'items', 'tab'));
    }

    public function showEditForm()
    {
        $user = Auth::user();
        $isFirstLogin = !$user->address()->exists();
        return view('profile.edit', compact('user', 'isFirstLogin'));
    }

    public function updateProfile(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        $user = Auth::user();

        $user->name = $addressRequest->name;

        if ($profileRequest->hasFile('profile_image')) {
            $this->updateProfileImage($user, $profileRequest->file('profile_image'));
        }

        $user->save();

        $this->updateUserAddress($user, $addressRequest);

        return redirect()->route('profile.show')->with('success', 'プロフィールが更新されました');
    }

    public function showListings()
    {
        $user = Auth::user();
        $listedItems = $user->items()->latest()->paginate(10);
        return view('profile.listings', compact('listedItems'));
    }

    public function showPurchases()
    {
        $user = Auth::user();
        $purchasedItems = $user->purchases()->with('item')->latest()->paginate(10);
        return view('profile.purchases', compact('purchasedItems'));
    }

    private function getItemsBasedOnTab($user, $tab)
    {
        if ($tab === 'sell') {
            return $user->items()->latest()->get();
        } elseif ($tab === 'buy') {
            return $user->purchases()->with('item')->latest()->get()->pluck('item');
        }
        return collect();
    }

    private function updateProfileImage($user, $image)
    {
        if ($user->profile_img_url) {
            Storage::disk('public')->delete($user->profile_img_url);
        }
        $imagePath = $image->store('profile_images', 'public');
        $user->profile_img_url = '/storage/' . $imagePath;
    }

    private function updateUserAddress($user, $addressRequest)
    {
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'postal_code' => $addressRequest->postal_code,
                'address' => $addressRequest->address,
                'building' => $addressRequest->building
            ]
        );
    }
}
