<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;

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

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $profileValidator = Validator::make($request->all(), (new ProfileRequest())->rules(), (new ProfileRequest())->messages());
        $addressValidator = Validator::make($request->all(), (new AddressRequest())->rules(), (new AddressRequest())->messages());

        $errors = $profileValidator->errors()->merge($addressValidator->errors());

        if ($errors->isNotEmpty()) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        $user->name = $request->name;

        if ($request->hasFile('profile_image')) {
            $this->updateProfileImage($user, $request->file('profile_image'));
        }

        $user->save();

        $this->updateUserAddress($user, $request);

        return redirect()->route('items.index')->with('success', 'プロフィールが更新されました');
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