@extends('layouts.seller')

@section('title', 'Kelola Voucher - TechnoShop Seller')
@section('description', 'Kelola semua voucher untuk toko Anda di TechnoShop')

@section('content')
<div class="container px-6 mx-auto grid">
    <div class="page-header">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">Kelola Voucher Toko</h2>
            <a href="{{ route('seller.discounts.create') }}" 
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                + Buat Voucher Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Maks Penggunaan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terpakai</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($discounts as $discount)
                    <tr>
                        <td class="px-6 py-4">{{ $discount->name }}</td>
                        <td class="px-6 py-4 font-mono">{{ $discount->code }}</td>
                        <td class="px-6 py-4">
                            @foreach($discount->categories as $category)
                            <span class="bg-gray-100 px-2 py-1 rounded-full text-sm mr-1">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4">
                            {{ $discount->start_date->format('d M Y') }} - 
                            {{ $discount->end_date->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($discount->max_uses == 0)
                                <span class="text-gray-500">Unlimited</span>
                            @else
                                {{ $discount->max_uses }}x
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($discount->count == 0)
                            <span class="text-gray-500">Belum Dipakai</span>
                        @else
                            {{ $discount->used_count }}x
                        @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($discount->is_active && $discount->end_date->isFuture())
                            <span class="text-green-600">● Aktif</span>
                            @else
                            <span class="text-red-600">● Kadaluarsa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('seller.discounts.edit', $discount) }}" 
                                   class="text-blue-600 hover:text-blue-800">Edit</a>
                                <form action="{{ route('seller.discounts.destroy', $discount) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800"
                                            onclick="return confirm('Hapus voucher ini?')">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-gray-600 mb-2">Belum ada voucher yang dibuat</p>
                                <a href="{{ route('seller.discounts.create') }}" 
                                   class="text-blue-600 hover:text-blue-800 hover:underline">
                                    Buat voucher pertama Anda
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $discounts->links() }}
        </div>
    </div>
</div>
@endsection