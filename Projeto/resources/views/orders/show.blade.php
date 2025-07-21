@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:max-w-7xl lg:px-8">
        <!-- Cabeçalho verde -->
        <div class="bg-green-700 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Order Details</h2>
        </div>

        <!-- Container branco com bordas -->
        <div class="bg-white shadow rounded-b-lg divide-y divide-gray-200">
            <!-- Seção de informações do pedido -->
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Order ID</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Date</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total</p>
                        <p class="mt-1 text-sm text-gray-900">{{ number_format($order->total, 2, ',', '.') }} €</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="mt-1 text-sm text-gray-900">
                            <span
                                class="px-2 py-1 rounded-full text-xs
                                {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Seção de itens do pedido -->
            <div class="px-6 py-5">
                <h3 class="text-lg font-medium text-green-800 mb-4">Items</h3>
                <ul class="space-y-4">
                    @foreach ($order->products as $item)
                        <li class="flex items-start border-b border-gray-100 pb-4">
                            <div class="flex-shrink-0 h-20 w-20 rounded-md overflow-hidden">
                                <img src="{{ $item->image_url ?? 'https://via.placeholder.com/80' }}"
                                    alt="{{ $item->name }}" class="h-full w-full object-cover">
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-base font-medium text-green-900">{{ $item->name }}</h4>
                                    <p class="ml-4 text-sm font-medium text-green-800">
                                        {{ number_format($item->pivot->unit_price, 2, ',', '.') }} €
                                    </p>
                                </div>
                                <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                    <p>Quantity: {{ $item->pivot->quantity }}</p>
                                    <p>Subtotal: {{ number_format($item->pivot->subtotal, 2, ',', '.') }} €</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Rodapé com total -->
            <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-gray-900">Order Total</span>
                    <span class="text-lg font-bold text-green-800">{{ number_format($order->total, 2, ',', '.') }} €</span>
                </div>
            </div>
        </div>



        <!-- Botões de ação -->
        <div class="mt-6 flex justify-between">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Orders
            </a>

            @if ($order->status === 'pending' && Auth::user() && in_array(Auth::user()->type, ['member', 'board']))
                <a href="{{ route('orders.cancel.form', $order) }}"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                    Cancel Order
                </a>
            @endif
            @if ($order->status === 'pending' && Auth::user() && Auth::user()->type === 'employee')
                <form action="{{ route('orders.accept', $order) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Accept
                    </button>
                </form>
            @endif
            <a href="{{ route('cart.checkout.receipt', $order) }}"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Download Receipt PDF
            </a>
        </div>
    </div>
@endsection
