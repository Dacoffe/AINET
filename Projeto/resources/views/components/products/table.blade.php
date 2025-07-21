<div class="overflow-x-auto">
    <div class="relative" x-data="{ open: false }">
        <table class="min-w-full divide-y divide-gray-200 text-center">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>

                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('products.index', ['sort_by' => 'price', 'order' => request('order') === 'asc' && request('sort_by') === 'price' ? 'desc' : 'asc']) }}"
                           class="flex justify-center items-center gap-1">
                            Preço
                            <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'price' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </th>

                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('products.index', ['sort_by' => 'stock', 'order' => request('order') === 'asc' && request('sort_by') === 'stock' ? 'desc' : 'asc']) }}"
                           class="flex justify-center items-center gap-1">
                            Stock
                            <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'stock' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </th>

                    <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <a href="{{ route('products.index', ['sort_by' => 'discount', 'order' => request('order') === 'asc' && request('sort_by') === 'discount' ? 'desc' : 'asc']) }}"
                           class="flex justify-center items-center gap-1">
                            Discount
                            <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'discount' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7" />
                            </svg>
                        </a>
                    </th>

                    @if ($showView)
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Show</th>
                    @endif
                    @if ($showEdit)
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
                    @endif
                    @if ($showDelete)
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Delete STOCK</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Delete PRODUCT</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($products as $product)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">€
                            {{ number_format($product->price, 2) }}€/KG
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $product->stock }}</td>
                        @if ($product->discount)
                            <td class="px-6 py-4 whitespace-nowrap text-orange-900">
                            {{ $product->discount .'%-->'. number_format($product->price * (1 - $product->discount / 100), 2)}} €/KG
                        @else
                            <td class="px-6 py-4 whitespace-nowrap">
                            {{ 'N/A'}}
                        @endif
                        </td>
                        @if ($showView)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('products.show', $product) }}" class="text-green-600 underline hover:text-green-900">View</a>
                            </td>
                        @endif
                        @if ($showEdit)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('products.edit', $product) }}" class="text-yellow-600 underline hover:text-yellow-900">Edit</a>
                            </td>
                        @endif
                        @if ($showDelete)
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('products.destroyStock', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Delete ('.$product->name.')?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline hover:text-red-900">Delete STOCK</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    onsubmit="return confirm('Delete ('.$product->name.')?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 underline hover:text-red-900">Delete PRODUCT</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
