@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10 bg-[#f9f9ef] min-h-screen">
        <div class="max-w-5xl mx-auto">
            <!-- Progresso do Checkout -->
            <div class="text-center mb-10">
                <h1 class="text-3xl font-extrabold text-green-800 tracking-tight">Checkout</h1>
                <div class="mt-4 flex justify-center">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                                1</div>
                            <p class="ml-2 text-sm font-medium text-gray-500">Cart</p>
                        </div>
                        <div class="h-px w-16 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-green-800 flex items-center justify-center text-white font-bold">
                                2</div>
                            <p class="ml-2 text-sm font-medium text-gray-500">Order Details & Payment</p>
                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($outOfStockItems))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg shadow">
                    <p class="font-bold">Warning</p>
                    <p>Some items in your cart have insufficient stock. Delivery may be delayed for these items.</p>
                </div>
            @endif

            @if (!$canCheckout)
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow">
                    <p class="font-bold">Insufficient Funds</p>
                    <p>Your virtual card balance (€{{ number_format($cardBalance, 2) }}) is insufficient for this purchase
                        (€{{ number_format($total, 2) }}).</p>
                </div>
            @endif

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Order Summary -->
                <div
                    class="md:col-span-2 bg-white rounded-2xl shadow-xl p-8 flex flex-col justify-between border border-gray-100">
                    <div>
                        <h2 class="text-xl font-semibold text-green-700 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            Order Summary
                        </h2>

                        <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto pr-2">
                            @foreach ($cartItems as $id => $item)
                                <div class="py-4 flex justify-between items-center">
                                    <div class="flex items-center">
                                        <img src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}"
                                            class="h-16 w-16 object-cover rounded-lg border border-gray-200 shadow">
                                        <div class="ml-4">
                                            <h3 class="text-gray-900 font-semibold">{{ $item['name'] }}</h3>
                                            <p class="text-sm text-gray-500">
                                                {{ $item['quantity'] }} × €{{ number_format($item['price'], 2) }}
                                            </p>
                                            @if (isset($outOfStockItems[$id]) && $outOfStockItems[$id] < $item['quantity'])
                                                <p class="text-sm text-yellow-600 font-semibold">
                                                    Only {{ $outOfStockItems[$id] }} in stock
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-gray-900 font-bold text-lg">
                                        €{{ number_format($item['price'] * $item['quantity'], 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="border-t border-gray-200 mt-6 pt-6 space-y-2">
                            <div class="flex justify-between text-gray-700">
                                <span>Subtotal:</span>
                                <span>€{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-700">
                                <span>Shipping:</span>
                                <span>€{{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xl font-bold mt-2">
                                <span>Total:</span>
                                <span class="text-green-700">€{{ number_format($total, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Back to Cart -->
                    <div class="mt-auto pt-8">
                        <div class="flex justify-start">
                            <a href="{{ route('cart.index') }}"
                                class="px-6 py-2 border border-blue-700 rounded-lg text-blue-700 font-semibold hover:bg-blue-50 transition flex items-center shadow">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path d="M15 19l-7-7 7-7" />
                                </svg>
                                Back to Cart
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                    <h2 class="text-xl font-semibold text-green-700 mb-6">Shipping Information</h2>

                    <form action="{{ route('cart.checkout.store') }}" method="POST">
                        @csrf

                        <div class="space-y-5">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $defaultData['name'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-green-200 focus:border-green-400 transition">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email"
                                    value="{{ old('email', $defaultData['email'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-green-200 focus:border-green-400 transition">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $defaultData['phone'] ?? '') }}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-green-200 focus:border-green-400 transition">
                            </div>
                            <div>
                                <div>
                                    <label for="address" class="block text-sm font-medium text-gray-700">Shipping
                                        Address</label>
                                    <textarea id="address" name="address" required rows="4"
                                        class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-green-200 focus:border-green-400 transition resize-none">{{ old('default_delivery_address', $defaultData['default_delivery_address'] ?? '') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <label for="nif" class="block text-sm font-medium text-gray-700">NIF (Tax ID)</label>
                                <input type="text" id="nif" name="nif"
                                    value="{{ old('nif', $defaultData['nif'] ?? '') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 focus:ring-green-200 focus:border-green-400 transition">
                            </div>
                        </div>

                        <!-- Payment Method Section -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium mb-4">Payment Method</h3>
                            <div class="flex items-center gap-3">
                                <input type="hidden" name="payment_method" value="{{ $defaultPaymentType }}">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded font-semibold text-sm">
                                    @if ($defaultPaymentType === 'card')
                                        Virtual Card
                                    @elseif($defaultPaymentType === 'mbway')
                                        MBWay
                                    @elseif($defaultPaymentType === 'visa')
                                        Visa
                                    @elseif($defaultPaymentType === 'paypal')
                                        PayPal
                                    @else
                                        {{ ucfirst($defaultPaymentType) }}
                                    @endif
                                </span>
                                @if ($defaultPaymentType === 'card')
                                    <span class="text-gray-500 text-sm">Balance:
                                        <span class="font-bold text-green-700">€{{ number_format($cardBalance, 2) }}</span>
                                    </span>
                                    @if (!$canCheckout)
                                        <span class="text-red-500 ml-2">(Insufficient funds)</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <button type="submit" id="complete-purchase"
                            class="w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-3 px-4 rounded-lg shadow-lg hover:from-green-700 hover:to-green-600 text-lg font-bold mt-8 transition">
                            Complete Purchase
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
