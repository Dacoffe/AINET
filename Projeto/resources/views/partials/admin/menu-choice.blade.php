<div class="bg-white p-4 rounded-md shadow-md mb-2 justify-between flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-black-900 hover:text-blue-700">Dashboard</a>
    <a href="{{ route('products.index') }}" class="text-black-900 hover:text-blue-700">Products</a>

    <a href="{{ route('profiles.index') }}" class="text-black-900 hover:text-blue-700">Profiles</a>
    <a href="{{ route('categories.index') }}" class="text-black-900 hover:text-blue-700">Categories</a>
    <a href="{{ route('orders.index') }}" class="text-black-900 hover:text-blue-700">Orders</a>
    <a href="{{ route('stock.index') }}" class="text-black-900 hover:text-blue-700">
        Low Stocks
        @php
            // Conta produtos com stock baixo (exemplo: < 10)
            $lowStockCount = \App\Models\Product::where('stock', '<', 10)->count();
        @endphp
        @if ($lowStockCount > 0)
            <span
                class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-red-600 text-white text-xs font-bold">
                {{ $lowStockCount }}
            </span>
        @endif
    </a>
    <a href="{{ route('supply_orders.index') }}" class="text-black-900 hover:text-blue-700">Supply Orders</a>
</div>
