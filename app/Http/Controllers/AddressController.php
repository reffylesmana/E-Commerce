<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'address_type' => 'required|in:home,office,other',
        ]);

        // Check if this is the first address (make it default)
        $isDefault = !Address::where('user_id', Auth::id())->exists();

        // If user is setting this as default, unset all other defaults
        if ($request->has('is_default') && $request->is_default) {
            Address::where('user_id', Auth::id())
                ->update(['is_default' => false]);
            $isDefault = true;
        }

        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'is_default' => $isDefault,
            'address_type' => $request->address_type,
        ]);

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function update(Request $request, Address $address)
    {
        // Check if address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->route('addresses.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengedit alamat ini.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'district' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'full_address' => 'required|string',
            'address_type' => 'required|in:home,office,other',
        ]);

        // If user is setting this as default, unset all other defaults
        if ($request->has('is_default') && $request->is_default) {
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);
            $address->is_default = true;
        }

        $address->update([
            'name' => $request->name,
            'recipient_name' => $request->recipient_name,
            'phone' => $request->phone,
            'province' => $request->province,
            'city' => $request->city,
            'district' => $request->district,
            'postal_code' => $request->postal_code,
            'full_address' => $request->full_address,
            'address_type' => $request->address_type,
        ]);

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $address)
    {
        // Check if address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->route('addresses.index')
                ->with('error', 'Anda tidak memiliki akses untuk menghapus alamat ini.');
        }

        // If deleting default address, set another as default if available
        if ($address->is_default) {
            $newDefault = Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->first();
            
            if ($newDefault) {
                $newDefault->is_default = true;
                $newDefault->save();
            }
        }

        $address->delete();

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        // Check if address belongs to user
        if ($address->user_id !== Auth::id()) {
            return redirect()->route('addresses.index')
                ->with('error', 'Anda tidak memiliki akses untuk mengubah alamat ini.');
        }

        // Unset all other defaults
        Address::where('user_id', Auth::id())
            ->update(['is_default' => false]);

        // Set this address as default
        $address->is_default = true;
        $address->save();

        return redirect()->route('addresses.index')
            ->with('success', 'Alamat utama berhasil diubah.');
    }
}