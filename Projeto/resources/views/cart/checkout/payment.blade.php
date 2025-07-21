@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
<!-- Progresso do Checkout -->
<div class="text-center mb-8">
</h1>
    <div class="mt-4 flex justify-center">
        <div class="flex items-center space-x-4">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">1</div>
                <p class="ml-2 text-sm font-medium text-gray-500">Cart</p>
            </div>
            <div class="h-px w-16 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">2</div>
                <p class="ml-2 text-sm font-medium text-gray-500">Order Details</p>
            </div>
            <div class="h-px w-16 bg-gray-300"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full bg-green-800 flex items-center justify-center text-white font-bold">3</div>
                <p class="ml-2 text-sm font-medium text-gray-500">Payment</p>
            </div>
        </div>
    </div>
</div>

            @if (!empty($outOfStockItems))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                    <p class="font-bold">Warning</p>
                    <p>Some items in your cart have insufficient stock. Delivery may be delayed for these items.</p>
                </div>
            @endif

            @if (!$canCheckout)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                    <p class="font-bold">Insufficient Funds</p>
                    <p>Your virtual card balance (€{{ number_format($cardBalance, 2) }}) is insufficient for this purchase
                        (€{{ number_format($total, 2) }}).</p>
                </div>
            @endif

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Order Summary -->
                <div class="md:col-span-2 bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-4">Order Summary</h2>

                    <div class="divide-y divide-gray-200">
                        @foreach ($cartItems as $id => $item)
                            <div class="py-4 flex justify-between">
                                <div class="flex items-center">
                                    <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                        class="h-16 w-16 object-cover rounded">
                                    <div class="ml-4">
                                        <h3 class="text-gray-900">{{ $item['name'] }}</h3>
                                        <p class="text-sm text-gray-500">
                                            {{ $item['quantity'] }} × €{{ number_format($item['price'], 2) }}
                                        </p>
                                        @if (isset($outOfStockItems[$id]) && $outOfStockItems[$id] < $item['quantity'])
                                            <p class="text-sm text-yellow-600">
                                                Only {{ $outOfStockItems[$id] }} in stock
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-gray-900">
                                    €{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-200 mt-4 pt-4 space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>€{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>€{{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span>€{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-medium mb-4">Shipping Information</h2>

                    <form action="{{ route('cart.checkout.store') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $defaultData['name'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $defaultData['email'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $defaultData['phone'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">Shipping
                                    Address</label>
                                <textarea id="address" name="address" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">{{ old('default_delivery_address', $defaultData['default_delivery_address'] ?? '') }}</textarea>
                            </div>
                            <div>
                                <label for="nif" class="block text-sm font-medium text-gray-700">NIF (Tax ID)</label>
                                <input type="text" id="nif" name="nif"
                                    value="{{ old('nif', $defaultData['nif'] ?? '') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3">
                            </div>
                        </div>
                        <!-- Payment Method Section -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium mb-4">Payment Method</h3>
                            <div class="space-y-4">
                                <!-- Virtual Card (Método real) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment-card" name="payment_method" type="radio" value="card"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300"
                                            {{ $canCheckout ? 'checked' : 'disabled' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment-card" class="font-medium text-gray-700">Virtual Card</label>
                                        <p class="text-gray-500">
                                            Balance: €{{ number_format($cardBalance, 2) }}
                                            @if (!$canCheckout)
                                                <span class="text-red-500">(Insufficient funds)</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- MBWay (Método fictício) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment-mbway" name="payment_method" type="radio" value="mbway"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment-mbway" class="font-medium text-gray-700">MBWay</label>
                                        <p class="text-gray-500">Example payment method</p>
                                    </div>
                                </div>

                                <!-- Visa (Método fictício) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment-visa" name="payment_method" type="radio" value="visa"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment-visa" class="font-medium text-gray-700">Visa</label>
                                        <p class="text-gray-500">Example payment method</p>
                                    </div>
                                </div>

                                <!-- PayPal (Método fictício) -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payment-paypal" name="payment_method" type="radio" value="paypal"
                                            class="focus:ring-green-500 h-4 w-4 text-green-600 border-gray-300">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payment-paypal" class="font-medium text-gray-700">PayPal</label>
                                        <p class="text-gray-500">Example payment method</p>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('cart.checkout.store') }}">
                                    <a href="{{ route('cart.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                                        <i class="fas fa-arrow-left mr-2"></i> Back to Cart
                                    </a>
                                    <button type="submit" id="complete-purchase"
                                    class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 mt-6">
                                    Complete Purchase
                                </button>
                            </form>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
