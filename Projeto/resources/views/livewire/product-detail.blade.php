<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">
    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8">
        <!-- Product Image -->
        <div class="aspect-square w-full rounded-md bg-gray-200 overflow-hidden">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
        </div>

        <!-- Product Details -->
        <div class="mt-10 px-4 sm:mt-16 sm:px-0 lg:mt-0">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ $product->name }}</h1>

            <div class="mt-6">
                <h3 class="sr-only">Description</h3>
                <div class="space-y-6 text-base text-gray-700">
                    <p>{{ $product->description }}</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center">
                    <p class="text-3xl tracking-tight text-gray-900">{{ number_format($product->price, 2) }}€/KG</p>
                </div>
            </div>

            <div class="mt-6">
                <div class="flex items-center gap-4">
                    <input type="number" wire:model="quantity" min="1" class="w-20 border rounded px-2 py-1">
                    <button wire:click="addToCart" class="flex max-w-xs flex-1 items-center justify-center rounded-md border border-transparent bg-green-700 px-8 py-3 text-base font-medium text-white hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-50 sm:w-full">
                        Add to cart
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    Livewire.on('notify', (message) => {
        alert(message);
    });
</script>
@endpush
