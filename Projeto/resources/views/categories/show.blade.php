{{-- filepath: resources/views/categories/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 space-y-10">

    <!-- Imagem da Categoria -->
    <div class="relative h-80 rounded-3xl overflow-hidden shadow-xl">
        <img
            src="{{ $category->image_url }}"
            alt="{{ $category->name }}"
            class="w-full h-full object-cover transition duration-300 hover:scale-105"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-black/30 to-transparent"></div>
        <div class="absolute bottom-4 left-6">
            <h1 class="text-4xl font-black text-white drop-shadow-md">{{ $category->name }}</h1>
        </div>
    </div>

    <!-- Produtos -->
    <div class="bg-white shadow-2xl ring-1 ring-gray-200 rounded-3xl p-10 space-y-6">
        @if($products->count())
            <h2 class="text-2xl font-semibold text-gray-800">
                {{ $products->count() }} produto{{ $products->count() > 1 ? 's' : '' }} nesta categoria
            </h2>

            <div class="divide-y divide-gray-200 rounded-xl border border-gray-100 bg-gray-50">
                @foreach ($products as $product)
                    <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-100 transition-colors">
                        <div>
                            <p class="text-lg font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-sm text-gray-500">
                                Stock: <span class="font-semibold text-gray-800">{{ $product->stock }}</span>
                            </p>
                        </div>
                        <a href="{{ route('products.show', $product) }}"
                           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition-all">
                            Ver Produto
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-base">Não existem produtos nesta categoria.</p>
        @endif
    </div>

</div>
@endsection
