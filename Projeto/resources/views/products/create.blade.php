@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white shadow-lg rounded-2xl p-6">

            <!-- Product Image Upload -->
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" for="image_file">Product Image</label>
                    <input type="file" name="image_file" id="image_file" class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg shadow-sm file:bg-gray-100 file:border-0 file:py-2 file:px-4 file:rounded file:text-sm file:font-semibold hover:file:bg-gray-200">
                    @error('image_file')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Product Form -->
            <div class="space-y-6">
                <!-- Name & Category -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input
                        name="name"
                        label="Name"
                        class="w-full"
                        :value="old('name')" />

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="category_id" id="category"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option disabled selected value="">Select a category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="3"
                        class="block w-full rounded-lg border-gray-300 shadow-sm p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Write discription here...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price, Discount, Discount Min Qty & Stock -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <flux:input
                        name="price"
                        label="Price"
                        :value="old('price')" />

                    <flux:input
                        name="discount"
                        label="Discount"
                        :value="old('discount')" />

                    <flux:input
                        name="discount_min_qty"
                        label="Discount Min Qty"
                        :value="old('discount_min_qty')" />

                    <flux:input
                        name="stock"
                        label="Stock"
                        :value="old('stock')" />
                </div>

                <!-- Stock Limits -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <flux:input
                        name="stock_lower_limit"
                        label="Stock Lower Limit"
                        :value="old('stock_lower_limit')" />

                    <flux:input
                        name="stock_upper_limit"
                        label="Stock Upper Limit"
                        :value="old('stock_upper_limit')" />
                </div>

                <!-- Submit button -->
                <div class="flex justify-end mt-2">
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white font-semibold px-5 py-2 rounded shadow text-sm">
                        Create Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
