@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
        <h1 class="text-3xl font-bold mb-8">{{ $category->name }}</h1>
        
        @include('partials.filters')
        
        <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            @if($products->isEmpty())
                <p class="text-center text-gray-700 col-span-full">No products in this category.</p>
            @else
                @foreach ($products as $product)
                    @include('partials.product-card', ['product' => $product])
                @endforeach
            @endif
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
@endsection