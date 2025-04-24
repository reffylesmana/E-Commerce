@extends('layouts.seller')

@section('title', 'Buat Voucher Baru - TechnoShop Seller')
@section('description', 'Buat voucher baru untuk toko Anda di TechnoShop')

@section('content')
    <div class="container px-6 mx-auto grid mt-4">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('seller.discounts.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Voucher</label>
                        <input type="text" name="name" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode Voucher</label>
                        <input type="text" name="code" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Voucher</label>
                        <select name="type" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="percentage">Persentase</option>
                            <option value="fixed">Nominal Tetap</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Voucher</label>
                        <input type="number" name="value" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <select name="categories[]" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Berakhir</label>
                        <input type="date" name="end_date" class="w-full border rounded-lg px-4 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="is_active" class="w-full border rounded-lg px-4 py-2" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Maksimal Penggunaan</label>
                        <input type="number" 
                               name="max_uses" 
                               min="1" 
                               value="{{ old('max_uses', 1) }}" 
                               class="w-full border rounded-lg px-4 py-2" 
                               required>
                        <p class="text-sm text-gray-500 mt-1">Jumlah maksimal penggunaan voucher (0 untuk tak terbatas)</p>
                    </div>
                </div>

                <div class="mt-6 flex gap-4">
                    <a href="{{ route('seller.discounts.index') }}"
                        class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                        Batal
                    </a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Simpan Voucher
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection
