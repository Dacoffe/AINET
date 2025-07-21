<!-- Adiciona isto no <head> do teu layout, se ainda não tiveres -->
<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<!-- Top Navbar -->
@if (Auth::user() && Auth::user()->type == 'board')
    <div class="bg-blue-900 text-white text-sm py-2 px-4 flex justify-between">
    <span>Welcome to our Grocery Store!</span>
    <span>ADMIN MODE || Account Balance {{ Auth::user()->card->balance }} €</span>
@elseif(Auth::user() && (Auth::user()->type === 'member' || Auth::user()->type === 'board'))
    <div class="bg-green-900 text-white text-sm py-2 px-4 flex uppercase justify-between">
    <span>Welcome to our Grocery Store! Account Balance {{ Auth::user()->card->balance }} €</span>
@else
    <div class="bg-green-900 text-white text-sm py-2 px-4 flex justify-between">
    <span>Welcome to our Grocery Store!</span>
@endif
    <div class="text-white text-sm py-2 px-4 flex uppercase justify-between">Projeto Alterado por David Ferreira 2221859</div>
<div class="items-end px-6 gap-4 flex">
    @auth
        <div class="flex items-center gap-2">
            <span class="text-white">Hi, {{ Auth::user()->name }}!</span>
        </div>
    @endauth
    @guest
        <a href="{{ route('login', ['redirect' => request()->path()]) }}" class="underline hover:text-gray-200">Sign In</a>
        <a href="{{ route('register', ['redirect' => request()->path()]) }}"
            class="underline hover:text-gray-200">Register</a>
    @endguest
</div>
</div>

<!-- Main Navbar -->
<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
            <span class="text-xl font-bold text-green-900">GROCERY CLUB</span>
        </div>

        <nav class="hidden md:flex items-center gap-6 text-green-900 font-semibold">
            @if (Auth::user() && Auth::user()->type == 'board')
                <a href="{{ route('admin.dashboard') }}" class="hover:text-green-700">Business Settings</a>
            @endif

            <a href="{{ route('home.index') }}" class="hover:text-green-700">Products</a>

            <!-- Categories Button with Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="hover:text-green-700 focus:outline-none flex items-center gap-1">
                    Categories
                    <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Categories Dropdown -->
                <div x-show="open" x-cloak @click.away="open = false"
                    class="absolute top-full left-1/2 transform -translate-x-1/2 z-50 w-[1000px] bg-white border border-gray-200 rounded-lg shadow-lg p-6 mt-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Shop by Category</h3>
                    </div>
                    <div class="grid grid-cols-5 gap-4">
                        @foreach ($categories as $category)
                            <a href="{{ route('category.show', $category->id) }}"
                                class="group relative block text-center">
                                <img src="{{ asset($category->image_url) }}" alt="{{ $category->name }}"
                                    class="h-20 mx-auto object-contain">
                                <span class="mt-2 block text-sm font-bold text-gray-800">{{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @if (Auth::user() && Auth::user()->type == 'employee')
                <a href="{{ route('orders.pending') }}" class="hover:text-green-700 flex items-center gap-1">
                    Pending Orders
                    @php
                        $pendingCount = App\Models\Order::where('status', 'pending')->count();
                    @endphp
                    @if ($pendingCount > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>
            @endif

        </nav>

        <div class="flex items-center gap-4">
            <form action="{{ route('home.search') }}" method="GET" class="flex">
                <input type="text" name="q" placeholder="Search..." value="{{ request('q') }}"
                    class="border rounded-l px-2 py-1 text-sm">
                <button type="submit" class="bg-green-700 text-white px-3 rounded-r">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>

            <!-- Cart Dropdown -->
            @include('cart.cart-dropdown')

            <!-- User Dropdown (visible when logged in) -->
            @auth
                <div class="relative ml-2" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" type="button"
                        class="flex items-center text-sm rounded-full focus:outline-none">
                        <span class="sr-only">Open user menu</span>
                        <img class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white font-bold"
                            src="{{ Auth::user()->PhotoFullUrl }}" alt="">
                    </button>
                    <div x-show="userMenuOpen" x-cloak @click.away="userMenuOpen = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                        <div class="px-4 py-2 border-b">
                            <p class="text-sm text-gray-700">Hello, {{ Auth::user()->firstLastName() }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('my-profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            My Profile
                        </a>
                        @if (Auth::user()->mailConfirmed())
                            <a href="{{ route('card.show') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Customer Card
                            </a>
                            @if(Auth::user()->isClubMember() || Auth::user()->isBoardMember() || Auth::user()->isEmployee())
                                <a href="{{ route('profile.my_orders') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    My Orders
                                </a>
                            @endif
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>
