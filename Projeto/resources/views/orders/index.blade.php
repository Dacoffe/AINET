@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Cabeçalho -->
        <div class="bg-blue-900 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Orders List</h2>
        </div>
        @include('partials.admin.menu-choice')
        <!-- Barra de busca -->
        <div class="bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('orders.index') }}">
                <div class="flex">
                    <input type="text" name="search" placeholder="Search by name..." value="{{ request('search') }}"
                        class="w-full px-4 py-2 border rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-lg">
                        Search
                    </button>
                    @if (request('search'))
                        <a href="{{ route('orders.index') }}"
                            class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>
        <x-orders.order-table :orders="$orders" :showView="true" :showEdit="true" :showDelete="true" />

        <!-- Paginação -->
        <div class="mt-6 flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
