@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="bg-blue-900 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Categories</h2>
        </div>

        @include('partials.admin.menu-choice')

        @if ($products->isEmpty())
            <div class="bg-yellow-100 text-yellow-800 px-4 py-3 rounded">
                Não existem produtos com stock baixo.
            </div>
        @else
            <div class="bg-white shadow rounded-b-lg overflow-hidden mt-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white rounded shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $product->name }}</td>
                                    <td class="px-4 py-2 text-red-600 font-bold">{{ $product->stock }}</td>
                                    <td class="px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                                    <td class="px-4 py-2 align-middle">
                                        <a href="{{ route('supply_orders.create', ['product_id' => $product->id]) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-900 border border-transparent rounded font-semibold text-xs text-white tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            New Order
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Paginação -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </div>
@endsection
