@extends('layouts.app')
@php
    $mode = $mode ?? 'edit';
    $readonly = $mode === 'show';
@endphp

@section('content')
<div class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
    <h2 class="text-3xl font-extrabold text-blue-900 mb-10 text-center">Order Details</h2>

    <div class="bg-white shadow-xl rounded-3xl p-8 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="space-y-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Order ID</label>
                    <div class="text-lg font-medium text-gray-800">{{ $order->id }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Status</label>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                @if ($order->status === 'pending')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="space-y-4 mt-4">
                        @csrf
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cancel Reason <span class="text-red-600">*</span></label>
                        <input type="text" name="cancel_reason" value="{{ old('cancel_reason') }}"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-red-400 focus:border-red-400" required>
                        @error('cancel_reason')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="from_edit" value="1">
                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2 rounded-lg shadow text-sm transition">
                                Cancel Order
                            </button>
                        </div>
                    </form>
                @elseif($order->status === 'canceled')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Cancel Reason</label>
                        <div class="text-gray-700 bg-gray-100 rounded-md px-3 py-2">{{ $order->cancel_reason }}</div>
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Total</label>
                    <div class="text-lg font-medium text-gray-800">€ {{ number_format($order->total, 2) }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Shipping</label>
                    <div class="text-gray-700 bg-gray-100 rounded-md px-3 py-2">€ {{ number_format($order->shipping_cost, 2) }}</div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Date</label>
                    <div class="text-gray-700 bg-gray-100 rounded-md px-3 py-2">{{ $order->date }}</div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase mb-3">Products</label>
                <ul class="divide-y divide-gray-200 bg-gray-50 rounded-lg shadow-inner">
                    @foreach ($products as $product)
                        <li class="flex justify-between items-center px-4 py-3">
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                            <span class="text-gray-500 text-sm">x{{ $product->pivot->quantity }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- Shipping update form --}}
    @if(isset($canEditShipping) && $canEditShipping)
        <div class="bg-blue-50 shadow rounded-2xl p-6 mb-8">
            <form action="{{ route('orders.update', $order->id) }}" method="POST" class="flex flex-col md:flex-row items-end gap-4">
                @csrf
                @method('PUT')
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Update Shipping</label>
                    <input type="number" step="0.01" name="shipping_cost"
                        value="{{ old('shipping_cost', $order->shipping_cost) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-400 focus:border-blue-400" required>
                </div>
                <button type="submit"
                    class="bg-blue-700 hover:bg-blue-800 text-white font-semibold px-6 py-2 rounded-lg shadow text-sm transition">
                    Update Shipping
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
