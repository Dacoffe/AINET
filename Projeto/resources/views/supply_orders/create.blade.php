{{-- filepath: resources/views/supply_orders/create.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:max-w-4xl lg:px-8">
        <div class="bg-blue-900 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">New Supply Order</h2>
        </div>

        <div class="bg-white shadow rounded-b-lg divide-y divide-gray-200">
            <form method="POST" action="{{ route('supply_orders.store') }}" class="px-6 py-5 space-y-6">
                @csrf

                <div>
                    <label for="product_id" class="block text-sm font-medium text-gray-700">Product</label>
                    <select name="product_id" id="product_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="">Select a product</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ old('product_id', $selectedProductId ?? '') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (ID: {{ $product->id }})
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Registered By</label>
                    <input type="hidden" name="registered_by_user_id" id="registered_by_user_id"
                        value="{{ Auth::id() }}">
                    <p class="mt-1 text-sm text-gray-900">{{ Auth::user()->name }} (ID: {{ Auth::id() }})</p>
                    @error('registered_by_user_id')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        <option value="requested" {{ old('status') == 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                    @error('status')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="quantity" id="quantity" min="1" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                        value="{{ old('quantity') }}">
                    @error('quantity')
                        <span class="text-red-600 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-between">
                    <a href="{{ route('supply_orders.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Create Supply Order
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
