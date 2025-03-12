@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['title' => 'Total Revenue', 'value' => '$45,231.89', 'change' => '+14.5%', 'icon' => 'heroicons-outline:trending-up', 'color' => 'green'],
            ['title' => 'Active Users', 'value' => '2,342', 'change' => '+23.1%', 'icon' => 'heroicons-outline:users', 'color' => 'blue'],
            ['title' => 'New Orders', 'value' => '1,234', 'change' => '-5.2%', 'icon' => 'heroicons-outline:shopping-bag', 'color' => 'purple'],
            ['title' => 'Conversion Rate', 'value' => '3.2%', 'change' => '+2.4%', 'icon' => 'heroicons-outline:chart-pie', 'color' => 'red']
        ] as $stat)
        <div class="rounded-lg border bg-white dark:bg-gray-800 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $stat['title'] }}</h3>
                    <div class="text-2xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $stat['value'] }}</div>
                </div>
                <div class="p-3 rounded-full bg-{{ $stat['color'] }}-100 dark:bg-{{ $stat['color'] }}-900/20">
                    <iconify-icon icon="{{ $stat['icon'] }}" 
                                 class="text-2xl text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"></iconify-icon>
                </div>
            </div>
            <p class="mt-4 flex items-center text-sm {{ str_contains($stat['change'], '-') ? 'text-red-500' : 'text-green-500' }}">
                <iconify-icon icon="heroicons-outline:arrow-{{ str_contains($stat['change'], '-') ? 'down' : 'up' }}" 
                             class="mr-1 h-4 w-4"></iconify-icon>
                {{ $stat['change'] }} from last month
            </p>
        </div>
        @endforeach
    </div>
</div>
@endsection