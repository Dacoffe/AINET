<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-center">
        <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('categories.index', ['sort_by' => 'name', 'order' => request('sort_by') === 'name' && request('order') === 'asc' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Nome
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'name' && request('order') === 'desc' ? 'rotate-180' : '' }}"
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
            @if ($showDelete)
                <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
            @endif
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($categories as $category)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $category->name }}</td>
                @if ($showView)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('categories.show', $category) }}" class="text-green-600 underline hover:text-green-900">View</a>
                    </td>
                @endif
                @if ($showEdit)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('categories.edit', $category) }}" class="text-yellow-600 underline hover:text-yellow-900">Edit</a>
                    </td>
                @endif
                @if ($showDelete)
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 underline hover:text-red-900">Delete</button>
                        </form>
                    </td>
                @endif
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
