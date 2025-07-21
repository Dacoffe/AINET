@php
    $cart = session('cart', []);
    $inCart = isset($cart[$product->id]);
@endphp
<div class="group relative overflow-hidden bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
    <!-- Product main content -->
    <a href="{{ isset($product) ? route('products.show', $product->id) : '#' }}" class="block p-4">
        <div class="aspect-square w-full overflow-hidden rounded-md bg-gray-200">
            <img src="{{ $product->image_url ?? asset('images/default-product.png') }}"
                 alt="{{ $product->name ?? 'Product image' }}"
                 class="h-full w-full object-cover object-center group-hover:opacity-90 transition-opacity"
                 onerror="this.src='{{ asset('images/default-product.png') }}'">
        </div>

        <div class="mt-4 flex justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900 line-clamp-1">
                    {{ $product->name ?? 'Product Name' }}
                </h2>
                <p class="mt-1 text-sm text-gray-500 line-clamp-2">
                    {{ $product->description ?? 'Product description' }}
                </p>
            </div>

            @if ($product->discount)
                <div class="flex flex-col items-end ml-2 text-right">
                    <p class="text-xl font-semibold text-red-700">
                        {{ number_format($product->price * (1 - $product->discount / 100), 2) }}€
                    </p>
                </div>
            @else
                <p class="text-xl font-semibold text-gray-900 whitespace-nowrap ml-2">
                    {{ number_format($product->price, 2) }}€
                </p>
            @endif
        </div>
    </a>

    <!-- Add to Cart Button (bottom) -->
    <div class="absolute bottom-0 left-0 right-0 translate-y-[100%] group-hover:translate-y-0 transition-transform duration-300 bg-white border-t border-gray-100 px-4 py-3 shadow-inner">
        @if(isset($product))
            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                @csrf
                <div class="flex items-center gap-2">
                    <div class="flex-1">
                        <label for="quantity-{{ $product->id }}" class="sr-only">Quantity</label>
                        <input type="number" id="quantity-{{ $product->id }}" name="quantity"
                               value="1" min="1"
                               class="w-full border rounded px-2 py-1 text-sm text-center">
                    </div>
                    <button type="submit"
                            class="flex-shrink-0 bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm shadow">
                        <i class="fas fa-cart-plus mr-1"></i> Add to Cart
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
