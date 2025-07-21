@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Cabeçalho verde -->
        <div class="bg-green-700 rounded-t-lg px-6 py-4">
            <h1 class="text-2xl font-bold text-white">Pending Orders</h1>
        </div>

        <div class="bg-white shadow rounded-b-lg divide-y divide-gray-200">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                                ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-green-900">#{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $order->member?->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($order->total, 2, ',', '.') }} €
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap flex gap-2 items-center">
                                    <form action="{{ route('orders.accept', $order) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                            Accept
                                        </button>
                                    </form>
                                    <a href="{{ route('orders.show_Order', $order->id) }}"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-xs font-semibold">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 rounded-b-lg">
                <div class="flex justify-between items-center">
                    <span class="text-base font-medium text-gray-900">Total Pending Orders</span>
                    <span class="text-lg font-bold text-green-800">{{ $orders->total() }}</span>
                </div>
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
