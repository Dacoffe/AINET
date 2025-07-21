{{-- filepath: resources/views/profile/my_profile.blade.php --}}
@extends('layouts.app')

@section('content')
    <div x-data="{ editMode: false }" class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <!-- Título destacado fora do container -->
        <h2 class="text-xl sm:text-2xl font-bold text-white bg-blue-700 rounded-t-lg px-6 py-4 mb-0 w-full shadow"
            style="border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
            User Profile: {{ $user->name }}
        </h2>

        <!-- Container do perfil destacado -->
        <div class="bg-white rounded-b-xl shadow-lg border border-gray-200 px-6 py-8 w-full">
            @if (session('success'))
                <div class="mb-4 p-3 rounded bg-green-100 text-green-800 text-center font-semibold">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 rounded bg-red-100 text-red-800 text-center font-semibold">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 p-3 rounded bg-blue-100 text-blue-800 text-center font-semibold">
                    {{ session('info') }}
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-8">
                <!-- Profile Photo -->
                <div class="flex flex-col items-center md:w-1/3 gap-4">
                    <img src="{{ $user->photo ? asset('storage/users/' . $user->photo) : 'https://www.gravatar.com/avatar/?d=mp' }}"
                        alt="User photo" class="rounded-full shadow object-cover w-44 h-44 border-2 border-gray-200">
                </div>

                <!-- User Info -->
                <div class="md:w-2/3">
                    <form method="POST" action="{{ route('profiles.update', $user->id) }}" enctype="multipart/form-data"
                        id="profile-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4 text-sm">
                            <!-- Full Name -->
                            <div>
                                <label class="block text-gray-800 font-medium mb-1">Full Name:</label>
                                <span x-show="!editMode"
                                    class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ $user->name }}</span>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                    x-show="editMode" required>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-gray-800 font-medium mb-1">Email Address:</label>
                                <span x-show="!editMode"
                                    class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ $user->email }}</span>
                                <input type="email" name="email" value="{{ $user->email }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-sm"
                                    x-show="editMode" readonly>
                            </div>

                            <!-- Address -->
                            <div class="sm:col-span-2">
                                <label class="block text-gray-800 font-medium mb-1">Address:</label>
                                <span x-show="!editMode"
                                    class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ $user->default_delivery_address }}</span>
                                <input type="text" name="default_delivery_address"
                                    value="{{ old('default_delivery_address', $user->default_delivery_address) }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                    x-show="editMode">
                            </div>

                            <!-- Block -->
                            @if ($user->type === 'board')
                                <div>
                                    <label class="block text-gray-800 font-medium mb-1">Block:</label>
                                    <span x-show="!editMode"
                                        class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ $user->blocked }}</span>
                                    <input type="text" name="blocked" value="{{ old('blocked', $user->blocked) }}"
                                        class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-sm"
                                        x-show="editMode" readonly>
                                </div>
                            @endif

                            <!-- NIF -->
                            <div>
                                <label class="block text-gray-800 font-medium mb-1">NIF:</label>
                                <span x-show="!editMode"
                                    class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ $user->nif }}</span>
                                <input type="text" name="nif" value="{{ old('nif', $user->nif) }}"
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-white text-sm"
                                    x-show="editMode">
                            </div>

                            <!-- Virtual Card Balance -->
                            <div>
                                <label class="block text-gray-800 font-medium mb-1">Virtual Card Balance:</label>
                                <input type="text" value="{{ number_format($user->card->balance ?? 0, 2) }} €" disabled
                                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 text-sm">
                            </div>

                            <!-- Member Type -->
                            <div class="sm:col-span-2">
                                <label class="block text-gray-800 font-medium mb-1">Member:</label>
                                @if ($user->type !== 'board')
                                    <span class="block px-3 py-2 bg-gray-50 rounded border border-transparent">
                                        {{ ucfirst($user->type) }}
                                    </span>
                                @else
                                    <span x-show="!editMode"
                                        class="block px-3 py-2 bg-gray-50 rounded border border-transparent">{{ ucfirst($user->type) }}</span>
                                    <select name="type" id="type"
                                        class="w-full px-3 py-2 bg-white rounded border border-gray-300 text-sm appearance-none"
                                        x-show="editMode">
                                        @foreach ($types as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type', $user->type) == $type ? 'selected' : '' }}>
                                                {{ ucfirst($type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <!-- Change Profile Picture (edit mode only) -->
                            <div class="sm:col-span-2" x-show="editMode">
                                <label class="block text-gray-800 font-medium text-sm mb-1 mt-2">
                                    Change Profile Picture:
                                </label>
                                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2">
                                    <input type="file" name="photo" accept="image/*"
                                        class="block text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3
                                            file:rounded file:border-0 file:text-sm file:font-semibold
                                            file:bg-green-100 file:text-green-700 hover:file:bg-green-200">
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mt-6 gap-4">
                            <!-- Botão Back (só fora do modo edição) -->
                            <a href="{{ session('previous_url') ?? route('admin.dashboard') }}"
                                class="w-32 h-10 bg-gray-700 hover:bg-gray-900 text-white rounded font-bold text-base flex items-center justify-center shadow-lg transition"
                                x-show="!editMode">
                                Back
                            </a>

                            @if (Auth::id() === $user->id)
                                <a href="{{ route('password.change') }}"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-5 py-2 rounded shadow text-sm ml-2"
                                    x-show="!editMode">
                                    Change Password
                                </a>
                            @endif

                            @if (Auth::id() === $user->id || Auth::user()->type !== 'employee')
                                <!-- Botão Edit (só fora do modo edição) -->
                                <button type="button"
                                    class="w-32 h-10 bg-blue-700 hover:bg-blue-900 text-white rounded font-bold text-base shadow-lg transition"
                                    x-show="!editMode" x-on:click="editMode = true">
                                    Edit
                                </button>
                            @endif

                            <!-- Botões de edição (só em modo edição) -->
                            <div class="flex gap-4 w-full justify-end" x-show="editMode">
                                <button type="button"
                                    class="w-32 h-10 bg-gray-700 hover:bg-gray-900 text-white rounded font-bold text-base shadow-lg transition"
                                    x-on:click="editMode = false">
                                    Cancel
                                </button>

                                @if ($user->type === 'board')
                                    <!-- Formulário para bloquear -->
                                    <form action="{{ route('users.block', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-32 h-10 bg-red-600 hover:bg-red-700 text-white rounded font-bold text-base shadow-lg transition">
                                            Block User
                                        </button>
                                    </form>

                                    <!-- Formulário para desbloquear -->
                                    <form action="{{ route('users.unblock', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-32 h-10 bg-green-600 hover:bg-green-700 text-white rounded font-bold text-base shadow-lg transition">
                                            Unblock User
                                        </button>
                                    </form>
                                @endif
                                @if (Auth::user()->type !== 'employee')
                                    <button type="submit"
                                        class="w-32 h-10 bg-green-600 hover:bg-green-700 text-white rounded font-bold text-base shadow-lg transition">
                                        Save Changes
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
