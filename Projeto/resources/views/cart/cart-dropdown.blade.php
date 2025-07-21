<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative">
        <svg class="w-6 h-6 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 4.293a1 1 0 00.217.957l.083.07A1 1 0 007 19h10a1 1 0 00.993-.883L19 18l-1-4H7z" />
        </svg>
        <span class="absolute -top-2 -right-2 text-xs bg-red-600 rounded-full px-1 text-white cart-count">
            {{ count(session('cart', [])) }}
        </span>
    </button>

    <!-- Dropdown panel -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-72 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
        <div class="py-1" id="cart-dropdown-content">
            @if(count(session('cart', [])) > 0)
                <div class="px-4 py-2 border-b">
                    <p class="text-sm font-medium text-gray-700">Your Cart (<span class="cart-count">{{ count(session('cart', [])) }}</span>)</p>
                </div>

                <div class="max-h-60 overflow-y-auto" id="cart-items-container">
                    @foreach(session('cart', []) as $id => $item)
                    <div class="px-4 py-3 border-b hover:bg-gray-50 cart-item" data-id="{{ $id }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover"
                                     src="{{ $item['image_url'] ?? asset('images/default-product.png') }}"
                                     alt="{{ $item['name'] }}">
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $item['quantity'] }} × {{ number_format($item['price'], 2) }}€
                                </p>
                            </div>
                            <div class="ml-2">
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="px-4 py-3 border-t">
                    <div class="flex justify-between text-sm font-medium">
                        <span>Subtotal:</span>
                        <span class="cart-subtotal">{{ number_format(array_reduce(session('cart', []), function($carry, $item) { return $carry + ($item['price'] * $item['quantity']); }, 0), 2) }}€</span>
                    </div>
                </div>

                <div class="px-4 py-2 bg-gray-50 flex justify-between">
                    <a href="{{ route('cart.index') }}"
                       class="text-sm text-green-700 hover:text-green-900 font-medium">
                        View Cart
                    </a>
                    @auth
                        <a href="{{ route('cart.checkout.index') }}"
                           class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                            Checkout
                        </a>
                    @else
                        <a href="{{ route('login', ['redirect' => 'cart.index']) }}"
                           class="text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">
                            Login to Checkout
                        </a>
                    @endauth
                </div>
            @else
                <div class="px-4 py-4 text-center">
                    <p class="text-sm text-gray-500">Your cart is empty</p>
                    <a href="{{ route('home.index') }}" class="mt-2 inline-block text-sm text-green-600 hover:text-green-800">
                        Continue Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
