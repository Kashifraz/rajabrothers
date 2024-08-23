<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Categories -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700">Total Categories</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalCategories }}</p>
                </div>

                <!-- Total Products -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700">Total Products</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</p>
                </div>

                <!-- Out of Stock Products -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700">Out of Stock</h3>
                    <p class="text-4xl font-bold text-red-600 mt-2">{{ $outOfStockProducts }}</p>
                </div>

                <!-- Total Orders -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700">Total Orders</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2">{{ $totalOrders }}</p>
                </div>
            </div>

            <!-- Optional Section for Other Details -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

