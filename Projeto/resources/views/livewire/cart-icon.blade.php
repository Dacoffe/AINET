<div>
    <a href="{{ route('cart') }}" class="relative" wire:poll.keep-alive>
        <svg class="w-6 h-6 text-green-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.293 4.293a1 1 0 00.217.957l.083.07A1 1 0 007 19h10a1 1 0 00.993-.883L19 18l-1-4H7z" />
        </svg>
        <span class="absolute -top-2 -right-2 text-xs bg-red-600 rounded-full px-1 text-white">{{ $cartCount }}</span>
    </a>
</div>
