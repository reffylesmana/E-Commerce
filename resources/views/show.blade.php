@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Product Images -->
        <div class="space-y-4">
            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                <img src="{{ $product->photos->first()?->photo ? Storage::url($product->photos->first()->photo) : asset('default-image.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
            </div>
            <div class="grid grid-cols-4 gap-4">
                @foreach($product->photos as $photo)
                    <button class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-md overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <img src="{{ $photo->photo ? Storage::url($photo->photo) : asset('default-image.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-center object-cover">
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 sm:text-4xl">{{ $product->name }}</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $product->category->name }}</p>
            </div>

            <div class="flex items-center">
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <svg class="h-5 w-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
                <p class="ml-2 text-sm text-gray-500">(42 reviews)</p>
            </div>

            <div class="mt-4 flex items-center">
                <h2 class="text-2xl font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</h2>
                @if($product->discount > 0)
                    <p class="ml-4 text-sm font-medium text-gray-500 line-through">Rp {{ number_format($product->original_price, 0, ',', '.') }}</p>
                    <p class="ml-2 text-sm font-medium text-red-500">{{ $product->discount }}% OFF</p>
                @endif
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-sm font-medium text-gray-900">Description</h3>
                <div class="mt-4 prose prose-sm text-gray-500">
                    {!! $product->description !!}
                </div>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-sm font-medium text-gray-900">Details</h3>
                <dl class="mt-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Size</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $product->size }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Weight</dt>
                        <dd class="text-sm font-medium text-gray-900">{{ $product->weight }}kg</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-gray-600">Stock</dt>
                        <dd class="text-sm font-medium {{ $product->stock > 10 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $product->stock > 0 ? $product->stock . ' available' : 'Out of stock' }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div class="mt-6">
                <form method="post" action="/carts/add/{{ $product->slug }}" class="space-y-4">
                    @csrf
                    @if($product->variations->count() > 0)
                        <div>
                            <label for="variation" class="block text-sm font-medium text-gray-700">Select Variation</label>
                            <select id="variation" name="variation_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                @foreach($product->variations as $variation)
                                    <option value="{{ $variation->id }}">{{ $variation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add to Cart
                    </button>
                </form>
                <button type="button" class="mt-4 w-full bg-gray-200 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    <svg class="h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    Add to Wishlist
                </button>
            </div>

            <div class="border-t border-gray-200 pt-4">
                <h3 class="text-sm font-medium text-gray-900">Shipping & Returns</h3>
                <p class="mt-4 text-sm text-gray-500">Free shipping on orders over Rp 1.000.000. 30-day return policy. <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Learn more</a></p>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-16 border-t border-gray-200 pt-10">
        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Customer Reviews</h2>
        <div class="mt-6 space-y-10">
            @foreach(range(1, 3) as $review)
                <div class="flex space-x-4">
                    <div class="flex-none">
                        <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="Reviewer">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">John Doe</p>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="h-5 w-5 {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Great product! Exactly as described and arrived quickly.</p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8 text-center">
            <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all reviews<span aria-hidden="true"> &rarr;</span></a>
        </div>
    </div>

    <!-- Related Products -->
    @if(count($productSuggets) > 0)
        <div class="mt-16 border-t border-gray-200 pt-10">
            <h2 class="text-2xl font-extrabold tracking-tight text-gray-900">Customers also purchased</h2>
            <div class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
                @foreach($productSuggets as $suggestedProduct)
                    <div class="group relative">
                        <div class="w-full min-h-80 bg-gray-200 aspect-w-1 aspect-h-1 rounded-md overflow-hidden group-hover:opacity-75 lg:h-80 lg:aspect-none">
                            <img src="{{ $suggestedProduct->photos->first()?->photo ? Storage::url($suggestedProduct->photos->first()->photo) : asset('default-image.jpg') }}" alt="{{ $suggestedProduct->name }}" class="w-full h-full object-center object-cover lg:w-full lg:h-full">
                        </div>
                        <div class="mt-4 flex justify-between">
                            <div>
                                <h3 class="text-sm text-gray-700">
                                    <a href="/products/{{ $suggestedProduct->slug }}">
                                        <span aria-hidden="true" class="absolute inset-0"></span>
                                        {{ $suggestedProduct->name }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-500">{{ $suggestedProduct->category->name }}</p>
                            </div>
                            <p class="text-sm font-medium text-gray-900">Rp {{ number_format($suggestedProduct->price, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection