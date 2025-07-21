{{-- filepath: resources/views/products/edit.blade.php --}}
@extends('layouts.app')
@php
    $mode = $mode ?? 'edit';
    $readonly = $mode === 'show';
@endphp

@section('content')
<div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white shadow-lg rounded-2xl p-6">
        @csrf
        @method('PUT')

        <!-- Product Image & Upload -->
        <div class="space-y-6">
            <div class="aspect-square w-full rounded-lg bg-gray-100 overflow-hidden border border-gray-200">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            @if(!$readonly)
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-1">Change Image</label>
                    <input type="file" name="photo" id="photo" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                    @error('photo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endif
        </div>

        <!-- Product Form -->
        <div class="space-y-6">
            <!-- Name & Category -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select name="category_id" id="category_id"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'disabled' : '' }}>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="block w-full rounded-md border-gray-300 shadow-sm p-3 text-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                    {{ $readonly ? 'readonly' : '' }}>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Price, Discount & Stock -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('price')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                    <input type="number" step="0.01" name="discount" id="discount" value="{{ old('discount', $product->discount) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('discount')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('stock')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Discount Min Qty, Stock Lower/Upper Limit -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="discount_min_qty" class="block text-sm font-medium text-gray-700 mb-1">Discount Min Qty</label>
                    <input type="number" name="discount_min_qty" id="discount_min_qty" value="{{ old('discount_min_qty', $product->discount_min_qty) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('discount_min_qty')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="stock_lower_limit" class="block text-sm font-medium text-gray-700 mb-1">Stock Lower Limit</label>
                    <input type="number" name="stock_lower_limit" id="stock_lower_limit" value="{{ old('stock_lower_limit', $product->stock_lower_limit) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('stock_lower_limit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="stock_upper_limit" class="block text-sm font-medium text-gray-700 mb-1">Stock Upper Limit</label>
                    <input type="number" name="stock_upper_limit" id="stock_upper_limit" value="{{ old('stock_upper_limit', $product->stock_upper_limit) }}"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 {{ $readonly ? 'bg-gray-100' : '' }}"
                        {{ $readonly ? 'readonly' : '' }}>
                    @error('stock_upper_limit')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded shadow text-sm">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
