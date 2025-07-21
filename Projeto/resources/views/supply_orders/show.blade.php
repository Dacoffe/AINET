{{-- filepath: resources/views/supply_orders/show.blade.php --}}
@extends('layouts.app')

@section('content')
    @if (session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-center font-semibold">
            {{ session('success') }}
        </div>
    @endif
    <div class="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:max-w-4xl lg:px-8">
        <!-- Cabeçalho azul -->
        <div class="bg-blue-900 rounded-t-lg px-6 py-4">
            <h2 class="text-xl font-bold text-white">Supply Order Details</h2>
        </div>

        <!-- Container branco com bordas -->
        <div class="bg-white shadow rounded-b-lg divide-y divide-gray-200">
            <div class="px-6 py-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Order ID</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $supplyOrder->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Product</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $supplyOrder->product->name ?? $supplyOrder->product_id }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Registered By</p>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $supplyOrder->user->name ?? $supplyOrder->registered_by_user_id }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold
                            @if ($supplyOrder->status === 'requested') bg-yellow-200 text-yellow-800
                            @elseif($supplyOrder->status === 'completed') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($supplyOrder->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Quantity</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $supplyOrder->quantity }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Created At</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $supplyOrder->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botões -->
        <div class="mt-6 flex justify-between">
            <a href="{{ route('supply_orders.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Supply Orders
            </a>
            @if ($supplyOrder->status === 'requested')
                <form action="{{ route('supply_orders.complete', $supplyOrder->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-800 focus:bg-green-800 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Mark as Completed
                    </button>
                </form>
            @endif
        </div>
    </div>
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                Livewire.restart();
            }
        });
    </script>
@endsection
