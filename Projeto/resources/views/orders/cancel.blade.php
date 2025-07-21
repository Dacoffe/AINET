@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:max-w-7xl lg:px-8">
        <h2 class="text-xl font-semibold mb-4">Cancel Order #{{ $order->id }}</h2>

        <form action="{{ route('orders.cancel', $order) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="cancel_reason" class="block text-sm font-medium text-gray-700">
                    Reason for Cancellation
                </label>
                <textarea id="cancel_reason" name="cancel_reason" rows="3"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2" required></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('cart.checkout.show', $order) }}"
                    class="px-4 py-2 bg-gray-300 rounded-md text-gray-800 hover:bg-gray-400">
                    To go back
                </a>
                <button type="submit" class="px-4 py-2 bg-red-600 rounded-md text-white hover:bg-red-700"
                    onclick="return confirm('Confirm order cancellation?')">
                    Confirm Cancellation
                </button>
            </div>
        </form>
    </div>
@endsection
