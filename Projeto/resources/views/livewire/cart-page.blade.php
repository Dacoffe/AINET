<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <h1 class="text-2xl font-bold mb-8">Your Shopping Cart</h1>

    <div class="bg-white p-6 rounded-lg shadow">
        @if($cartItems->isEmpty())
            <p class="text-center py-8">Your cart is empty</p>
        @else
            <div class="space-y-4">
                @foreach($cartItems as $item)
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center space-x-4">
                            <img src="{{ $item->attributes->image }}" alt="{{ $item->name }}" class="w-16 h-16 object-cover rounded">
                            <div>
                                <h3 class="font-medium">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $item->quantity }}kg × €{{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="font-bold">€{{ number_format($item->price * $item->quantity, 2) }}</span>
                            <button wire:click="removeFromCart('{{ $item->id }}')" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 border-t pt-6">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg">Total:</span>
                    <span class="font-bold text-lg">€{{ number_format($total, 2) }}</span>
                </div>
                <button class="mt-4 w-full bg-green-700 text-white py-2 px-4 rounded hover:bg-green-800 transition">
                    Proceed to Checkout
                </button>
            </div>
        @endif
    </div>
</div>
