@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center py-4">
        <div class="w-full max-w-5xl shadow rounded-lg p-6 bg-white">
            <!-- Header -->
            <h3 class="text-lg sm:text-xl font-semibold text-white bg-green-700 px-6 py-3 rounded-t mb-4">
                My Orders
            </h3>

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('profile.my_orders', ['sort_by' => 'id', 'order' => request('sort_by') === 'id' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="flex justify-center items-center gap-1">
                                    Order ID
                                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'id' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('profile.my_orders', ['sort_by' => 'created_at', 'order' => request('sort_by') === 'created_at' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="flex justify-center items-center gap-1">
                                    Date
                                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'created_at' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('profile.my_orders', ['sort_by' => 'total', 'order' => request('sort_by') === 'total' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="flex justify-center items-center gap-1">
                                    Total
                                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'total' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                            </th>
                            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="{{ route('profile.my_orders', ['sort_by' => 'status', 'order' => request('sort_by') === 'status' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                                    class="flex justify-center items-center gap-1">
                                    Status
                                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'status' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $order->id }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    {{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    {{ number_format($order->total, 2, ',', '.') }} €</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if ($order->status === 'completed') bg-green-100 text-green-800
                                @elseif($order->status === 'canceled') bg-red-100 text-red-800
                                @else bg-orange-100 text-orange-800 @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('orders.show_Order', $order->id) }}"
                                        class="text-blue-600 hover:text-blue-900">View Details</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Paginação -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endsection
