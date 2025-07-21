@extends('layouts.app')

@section('content')
<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8 bg-[#f9f9ef]">
    <div class="max-w-5xl mx-auto">

        <!-- Progresso do Checkout -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-green-800 tracking-tight">Your Cart</h1>
            <div class="mt-4 flex justify-center">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-green-800 flex items-center justify-center text-white font-bold">1</div>
                        <p class="ml-2 text-sm font-medium text-green-800">Cart</p>
                    </div>
                    <div class="h-px w-16 bg-green-200"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">2</div>
                        <p class="ml-2 text-sm font-medium text-gray-500">Order Details & Payment</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertas -->
        @if (!empty($outOfStockItems))
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6 rounded-lg shadow">
                <p class="font-bold">Warning</p>
                <p>Some items in your cart have insufficient stock. Delivery may be delayed for these items.</p>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow">
                {{ session('success') }}
            </div>
        @endif

        <!-- Carrinho vazio -->
        @if (empty($cartItems))
            <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
                <p class="text-gray-600 text-lg">Your cart is empty.</p>
                <a href="{{ route('home.index') }}" class="mt-6 inline-block px-6 py-2 rounded-lg border border-blue-700 text-blue-700 font-semibold hover:bg-blue-50 transition">
                    Continue Shopping
                </a>
            </div>
        @else
            <!-- Tabela de Produtos -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8 border border-gray-100">

                <!-- Corpo com Scroll -->
                <div class="max-h-[500px] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <img class="h-12 w-12 rounded-lg border border-gray-200 shadow" src="{{ $item['image_url'] }}" alt="{{ $item['name'] }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-base font-semibold text-gray-900">{{ $item['name'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-700">
                                        {{ number_format($item['price'], 2) }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="flex items-center">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="0" max="99"
                                                class="w-20 border rounded px-2 py-1 text-base mr-2">
                                            <button type="submit" class="text-sm text-green-600 hover:text-green-800 font-semibold">Update</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base text-gray-700 font-semibold">
                                        {{ number_format($item['price'] * $item['quantity'], 2) }}€
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-base font-medium">
                                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Subtotal -->
                <div class="border-t bg-gray-50 px-6 py-4 flex justify-end text-base font-medium text-gray-500">
                    <span class="mr-2">Subtotal:</span>
                    <span class="font-bold text-gray-900">{{ number_format($total, 2) }}€</span>
                </div>
            </div>

            <!-- Botões alinhados -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-8">
                <div class="flex gap-4">
                    <a href="{{ route('home.index') }}"
                       class="px-6 py-3 bg-gray-200 text-gray-800 font-bold rounded-lg hover:bg-gray-300 transition text-lg shadow-none">
                        Continue Shopping
                    </a>
                    <form action="{{ route('cart.clear') }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-6 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition text-lg shadow-none">
                            Clear Cart
                        </button>
                    </form>
                </div>
                @auth
                    @if(Auth::user()->type !== 'pending_member')
                        <a href="{{ route('cart.checkout.index') }}"
                           class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-lg shadow-none">
                            Proceed to Checkout
                        </a>
                    @else
                        <div class="text-red-600 text-center">
                            <p class="font-bold">Pending Membership</p>
                            <p>Please verify your email address to complete your membership and pay the membership fee.</p>
                        </div>
                    @endif
                @else
                    <a href="{{ route('login', ['redirect' => 'cart.index']) }}"
                       class="px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition text-lg shadow-none">
                        Login to Checkout
                    </a>
                @endauth
            </div>
        @endif

    </div>
</div>
@endsection
