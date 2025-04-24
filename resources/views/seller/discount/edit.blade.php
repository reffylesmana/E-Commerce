@extends('layouts.seller')

@section('title', 'Edit Voucher - TechnoShop Seller')
@section('description', 'Edit voucher untuk toko Anda di TechnoShop')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="page-header">
        <h2 class="text-2xl font-semibold mb-6">Edit Voucher</h2>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('seller.discounts.update', $discount) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tambahkan field type dan value yang hilang -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Voucher</label>
                    <input type="text" name="name" value="{{ old('name', $discount->name) }}" 
                           class="w-full border rounded-lg px-4 py-2" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Voucher</label>
                    <input type="text" name="code" value="{{ old('code', $discount->code) }}" 
                           class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Voucher</label>
                    <select name="type" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="percentage" {{ $discount->type == 'percentage' ? 'selected' : '' }}>Persentase</option>
                        <option value="fixed" {{ $discount->type == 'fixed' ? 'selected' : '' }}>Nominal Tetap</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Voucher</label>
                    <input type="number" name="value" value="{{ old('value', $discount->value) }}" 
                           class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <!-- Perbaikan select kategori (multiple) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="categories[]" class="w-full border rounded-lg px-4 py-2"required>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                            {{ in_array($category->id, $discount->categories->pluck('id')->toArray()) ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" name="start_date" 
                           value="{{ old('start_date', $discount->start_date->format('Y-m-d')) }}" 
                           class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                    <input type="date" name="end_date" 
                           value="{{ old('end_date', $discount->end_date->format('Y-m-d')) }}" 
                           class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="is_active" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="1" {{ $discount->is_active ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !$discount->is_active ? 'selected' : '' }}>Tidak Aktif</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Penggunaan</label>
                    <input type="number" 
                           name="max_uses" 
                           min="0" 
                           value="{{ old('max_uses', $discount->max_uses) }}" 
                           class="w-full border rounded-lg px-4 py-2" 
                           required>
                    <p class="text-sm text-gray-500 mt-1">
                        Penggunaan saat ini: {{ $discount->used_count }} kali
                    </p>
                </div>
            </div>
            
            <div class="mt-6 flex gap-4">
                <a href="{{ route('seller.discounts.index') }}" 
                   class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Update Voucher
                </button>
            </div>
        </form>
    </div>
</div>
@endsection