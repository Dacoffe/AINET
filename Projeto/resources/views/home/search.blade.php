@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <h1 class="text-2xl font-bold mb-8">Search Results for "{{ $query }}"</h1>

    @if($products->isEmpty())
        <div class="text-center py-12">
            <p class="text-gray-700 text-lg">No products found for "{{ $query }}"</p>
            <a href="{{ route('home.index') }}" class="mt-4 inline-block bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
                Back to Home
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            @foreach ($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->appends(['q' => $query])->links() }}
        </div>
    @endif
</div>
@endsection
