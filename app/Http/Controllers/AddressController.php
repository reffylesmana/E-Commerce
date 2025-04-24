<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            'full_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);
    
        DB::beginTransaction();
    
        try {
            if ($request->is_default) {
                Address::where('user_id', Auth::id())->update(['is_default' => false]);
            }
    
            $address = Address::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'full_address' => $request->full_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'is_default' => $request->is_default ?? false,
            ]);
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Alamat berhasil disimpan.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan alamat: ' . $e->getMessage()]);
        }
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'full_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);
    
        DB::beginTransaction();
    
        try {
            if ($request->is_default) {
                Address::where('user_id', Auth::id())->update(['is_default' => false]);
            }
    
            $address = Address::findOrFail($id);
            $address->update([
                'name' => $request->name,
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'full_address' => $request->full_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'is_default' => $request->is_default ?? false,
            ]);
    
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'Alamat berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat memperbarui alamat: ' . $e->getMessage()]);
        }
    }
    
    public function destroy($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->first();
    
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan.']);
        }
    
        $address->delete();
    
        return response()->json(['success' => true, 'message' => 'Alamat berhasil dihapus.']);
    }
    
    public function show($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->first();
    
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Alamat tidak ditemukan.']);
        }
    
        return response()->json($address);
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