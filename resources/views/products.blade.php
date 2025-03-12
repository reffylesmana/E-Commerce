@extends('layouts.app')

@section('content')
<div x-data="productList()" class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-gray-800 dark:text-white">Our Products</h1>

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Filters Sidebar -->
        <div class="lg:w-1/4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">Filters</h2>
                
                <!-- Category Filter -->
                <div class="mb-6">
                    <h3 class="font-medium mb-2 text-gray-700 dark:text-gray-300">Category</h3>
                    <div class="space-y-2">
                        <template x-for="category in categories" :key="category.id">
                            <label class="flex items-center space-x-2 text-gray-600 dark:text-gray-400">
                                <input type="checkbox" :value="category.id" x-model="selectedCategories" class="rounded border-gray-300 text-blue-600 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span x-text="category.name"></span>
 </label>
                        </template>
                    </div>
                </div>
                
                <!-- Price Filter -->
                <div>
                    <h3 class="font-medium mb-2 text-gray-700 dark:text-gray-300">Price Range</h3>
                    <div class="flex items-center space-x-4">
                        <input type="number" x-model="minPrice" placeholder="Min" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="text-gray-500">-</span>
                        <input type="number" x-model="maxPrice" placeholder="Max" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:w-3/4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-105">
                        <img :src="product.photos[0]?.url" :alt="product.name" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 x-text="product.name" class="text-lg font-semibold mb-2 text-gray-800 dark:text-white"></h3>
                            <p x-text="product.category.name" class="text-sm text-gray-600 dark:text-gray-400 mb-2"></p>
                            <p x-text="'$' + product.price.toFixed(2)" class="text-lg font-bold text-blue-600 dark:text-blue-400"></p>
                            <button @click="addToCart(product)" class="mt-4 w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors duration-300">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function productList() {
    return {
        products: @json($products),
        categories: [...new Set(@json($products).map(p => p.category.name))],
        selectedCategories: [],
        minPrice: '',
        maxPrice: '',
        
        get filteredProducts() {
            return this.products.filter(product => {
                const categoryMatch = this.selectedCategories.length === 0 || this.selectedCategories.includes(product.category.id);
                const priceMatch = (!this.minPrice || product.price >= this.minPrice) && 
                                   (!this.maxPrice || product.price <= this.maxPrice);
                return categoryMatch && priceMatch;
            });
        },
        
        addToCart(product) {
            console.log('Added to cart:', product);
        }
    }
}
</script>
@endsection