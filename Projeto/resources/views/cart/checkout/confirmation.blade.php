@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="bg-green-100 px-6 py-4 border-b border-green-200">
            <div class="flex items-center">
                <svg class="h-8 w-8 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <h1 class="text-xl font-semibold text-green-800">Order Confirmed!</h1>
            </div>
        </div>

        <div class="p-6">
            @if(isset($order->order_number))
                <p class="text-gray-700 mb-6">Thank you for your purchase! Your order #{{ $order->order_number }} is being prepared.</p>
            @else
                <p class="text-gray-700 mb-6">Thank you for your purchase! Your order is being prepared.</p>
            @endif

            @if(!empty($outOfStockItems) && count($outOfStockItems) > 0)
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                    <p class="font-bold">Notice</p>
                    <p>Delivery may be delayed for the following items due to low stock:</p>
                    <ul class="list-disc pl-5 mt-2">
                        @foreach($outOfStockItems as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <h2 class="text-lg font-medium mb-2">Order Summary</h2>

                @if($order->items && $order->items->count() > 0)
                    @foreach($order->items as $item)
                        <div class="flex justify-between py-2 border-b">
                            <span>
                                {{ $item->product->name ?? $item->name }}
                                (x{{ $item->quantity }})
                            </span>
                            <span>
                                {{ number_format($item->unit_price * $item->quantity, 2) }} €
                            </span>
                        </div>
                    @endforeach

                    <div class="flex justify-between font-bold mt-2">
                        <span>Subtotal:</span>
                        <span>{{ number_format($order->subtotal, 2) }} €</span>
                    </div>

                    <div class="flex justify-between">
                        <span>Shipping:</span>
                        <span>{{ number_format($order->shipping, 2) }} €</span>
                    </div>

                    <div class="flex justify-between font-bold mt-2 border-t pt-2">
                        <span>Total:</span>
                        <span>{{ number_format($order->total_amount, 2) }} €</span>
                    </div>
                @else
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6">
                        <p>No items found in this order.</p>
                    </div>
                @endif
            </div>

            @if(isset($order->delivery_address))
                <div class="bg-gray-50 p-4 rounded mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Shipping to</h3>
                    <p>{{ $order->delivery_address }}</p>
                </div>
            @endif

            <div class="flex space-x-4">
                <a href="{{ route('home.index') }}" class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-md">
                    Continue Shopping
                </a>
                @if(isset($order->id))
                    <a href="{{ route('orders.show', $order->id) }}" class="flex-1 text-center bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                        View Order Details
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
