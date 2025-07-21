<div class="bg-white p-4 rounded-md shadow-md mb-6">
    <form action="{{ request()->url() }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div>
            <label for="min_price" class="block text-sm font-medium text-gray-900">Min Price (€)</label>
            <input type="number" name="min_price" id="min_price" value="{{ request('min_price') }}"
                class="mt-1 block w-full border-gray-300  text-gray-700 rounded-md shadow-sm" placeholder="0">
        </div>

        <div>
            <label for="max_price" class="block text-sm font-medium text-gray-900">Max Price (€)</label>
            <input type="number" name="max_price" id="max_price" value="{{ request('max_price') }}"
                class="mt-1 block w-full border-gray-300 text-gray-700 rounded-md shadow-sm" placeholder="100">
        </div>

        <div>
            <label for="sort" class="block text-sm font-medium text-gray-900">Sort By</label>
            <select name="sort" id="sort" class="mt-1 block w-full border-gray-300 text-gray-700 rounded-md shadow-sm">
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A to Z</option>
                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z to A</option>
            </select>
        </div>

        <div class="md:col-span-4 flex justify-end space-x-2">
            <a href="{{ request()->url() }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                Clear
            </a>
            <button type="submit" class="px-4 py-2 bg-green-900 text-white rounded-md hover:bg-green-700">
                Apply Filters
            </button>
        </div>
    </form>
</div>
