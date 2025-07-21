@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:max-w-7xl lg:px-8">
        <!-- Cabeçalho verde -->
        <div class="bg-green-700 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Order #{{ $order->id }}</h2>
        </div>

        <!-- Container branco com bordas -->
        <div class="bg-white shadow rounded-b-lg divide-y divide-gray-200">
            <!-- Seção de informações do pedido -->
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Order Date</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1 text-sm text-gray-900 capitalize">{{ $order->status }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Shipping</p>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($order->shipping_cost, 2, ',', '.') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($order->total, 2, ',', '.') }} €</p>
                    </div>
                </div>
            </div>

            <!-- Seção de itens do pedido -->
            <div class="px-6 py-5">
                <h3 class="text-lg font-medium text-green-800 mb-4">Order Items</h3>
                <ul class="space-y-4">
                    @foreach ($order->products as $product)
                        <li class="flex items-start border-b border-gray-100 pb-4">
                            <div class="flex-shrink-0 h-20 w-20 rounded-md overflow-hidden">
                                <img src="{{ $product->image_url ?? 'https://via.placeholder.com/80' }}"
                                    alt="{{ $product->name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-medium text-green-900">{{ $product->name }}</h4>
                                    <p class="ml-4 text-sm font-medium text-green-800">
                                        {{ number_format($product->pivot->unit_price, 2, ',', '.') }} €
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                    <p>Quantity: {{ $product->pivot->quantity }}</p>
                                    <p>Subtotal: {{ number_format($product->pivot->subtotal, 2, ',', '.') }} €</p>
                                </div>

                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Rodapé com total e botões -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg flex justify-between items-center">
                <div>
                    <span class="text-base font-medium text-gray-900">Shipping Cost</span>
                    <span class="text-lg font-bold text-green-800 ml-2">
                        {{ number_format($order->shipping_cost, 2, ',', '.') }} €
                    </span>
                </div>
                <div>
                    <span class="text-base font-medium text-gray-900">Order Total</span>
                    <span class="text-lg font-bold text-green-800 ml-2">
                        {{ number_format($order->total, 2, ',', '.') }} €
                    </span>
                </div>

                <div class="space-x-2">
                    <a href="{{ route('home.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300">
                        Back to Shop
                    </a>
                    <a href="{{ route('cart.checkout.receipt', $order) }}"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Download PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
