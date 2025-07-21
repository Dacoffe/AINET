@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
            <!-- Product Image -->
            <div class="aspect-square w-full rounded-md bg-gray-200 overflow-hidden">
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            </div>

            <!-- Product Details -->
            <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
                <div class="flex justify-between">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>
                    <div>
                        <h3 class="text-3xl font-semibold tracking-tight text-gray-900">{{ $category->name }}</h3>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-xl font-semibold tracking-tight text-gray-900">Description</h3>
                    <div class="space-y-6 text-base text-gray-700">
                        <p>{{ $product->description }}</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-between items-center">
                    <!-- Preço à esquerda -->
                    <div class="flex items-center gap-2">
                        @if ($product->discount)
                            @php
                                $discountedPrice = $product->price * (1 - $product->discount / 100);
                            @endphp
                            <p class="text-3xl font-semibold text-red-600">{{ number_format($discountedPrice, 2) }}€/KG</p>
                            <p class="text-2xl tracking-tight text-gray-900 line-through">{{ number_format($product->price, 2) }}€/KG</p>
                        @else
                            <p class="text-3xl tracking-tight text-gray-900">{{ number_format($product->price, 2) }}€/KG</p>
                        @endif
                    </div>

                    <!-- Stock e Categoria à direita -->
                    <div class="text-right">
                        <div class="mt-4">
                            <h3 class="text-xl font-semibold tracking-tight text-gray-900">Stock</h3>
                            <p class="text-base text-gray-700">{{ $product->stock }} units</p>
                        </div>
                    </div>
                </div>


                <div class="mt-6">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <div class="flex items-center gap-4">
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                   class="w-20 border rounded px-2 py-1">
                            <button type="submit" class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-green-700 px-8 py-3 text-base font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">
                                Add to cart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
