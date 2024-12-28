<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $item = Item::findOrFail($id);
        return view('purchases.show', compact('item'));
    }
}
