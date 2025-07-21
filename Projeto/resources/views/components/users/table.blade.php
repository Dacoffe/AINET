<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        @if(session('success'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow text-sm">
                {{ session('success') }}
            </div>
        @endif


        <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('profiles.index', ['sort_by' => 'name', 'order' => request('order') === 'asc' && request('sort_by') === 'name' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Name
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'name' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </th>
            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('profiles.index', ['sort_by' => 'balance', 'order' => request('order') === 'desc' && request('sort_by') === 'balance' ? 'asc' : 'desc']) }}"
                   class="flex justify-center items-center gap-1">
                    Balance
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'balance' && request('order') === 'asc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </th>
            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('profiles.index', ['sort_by' => 'type', 'order' => request('order') === 'asc' && request('sort_by') === 'type' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    Type
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'type' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </th>
            <th scope="col" class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">
                <a href="{{ route('profiles.index', ['sort_by' => 'nif', 'order' => request('order') === 'asc' && request('sort_by') === 'nif' ? 'desc' : 'asc']) }}"
                   class="flex justify-center items-center gap-1">
                    NIF
                    <svg class="w-4 h-4 transition-transform {{ request('sort_by') === 'nif' && request('order') === 'desc' ? 'rotate-180' : '' }}"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </th>
            @if ($showView)
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Show</th>
            @endif
            @if ($showDelete)
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Delete</th>
            @endif
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($users as $user)
            <tr>
                <td class="px-6 py-4 text-center whitespace-nowrap">
                    {{ $user->name }}
                </td>
                <td class="px-6 py-4 text-center whitespace-nowrap">{{ number_format($user->card->balance ?? 0, 2)}} €</td>
                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $user->type }}</td>
                <td class="px-6 py-4 text-center whitespace-nowrap">{{ $user->nif ?? 'N/A' }}</td>
                @if ($showView)
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <a href="{{ route('profiles.show', $user->id) }}" class="text-green-600 underline hover:text-green-900">View</a>
                    </td>
                @endif
                @if ($showDelete)
                    <td class="px-6 py-4 text-center whitespace-nowrap">
                        <form action="{{ route('profiles.destroy', $user) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this profile?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 underline hover:text-red-900">Delete</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-4 text-gray-500">No profiles found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
