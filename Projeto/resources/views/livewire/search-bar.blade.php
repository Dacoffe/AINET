<div>
    <form wire:submit.prevent="$emit('searchUpdated', query)" class="flex">
        <input type="text" wire:model.debounce.300ms="query" placeholder="Search..."
            class="border rounded-l px-2 py-1 text-sm" />
        <button type="submit" class="bg-green-700 text-white px-3 rounded-r">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </form>
</div>
