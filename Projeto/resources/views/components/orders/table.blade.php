<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-center">
        <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('orders.index', ['sort_by' => 'id', 'order' => request('sort_by') === 'id' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Order ID
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'id' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('orders.index', ['sort_by' => 'created_at', 'order' => request('sort_by') === 'created_at' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Date
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'created_at' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('orders.index', ['sort_by' => 'status', 'order' => request('sort_by') === 'status' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Status
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'status' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </th>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('orders.index', ['sort_by' => 'total', 'order' => request('sort_by') === 'total' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Total
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'total' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7"/>
                    </svg>
                </a>
            </th>
            @if ($showView)
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Show</th>
            @endif
            @if ($showEdit)
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
            @endif
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($orders as $order)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">#{{ $order->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $order->created_at->format('d/m/Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">€{{ number_format($order->total, 2) }}</td>
                @if ($showView)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('orders.show_Order', $order) }}" class="text-green-600 underline hover:text-green-900">View</a>
                    </td>
                @endif
                @if ($showEdit)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('orders.edit', $order) }}"  class="text-yellow-600 underline hover:text-yellow-900">Edit</a>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
