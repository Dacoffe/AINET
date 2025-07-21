@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-16">
    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-xl ring-1 ring-gray-100 rounded-3xl overflow-hidden p-8 space-y-6">
        @csrf
        <!-- Upload imagem -->
        <div>
            <label for="image_file" class="block text-sm font-medium text-gray-700 mb-1">Category Image</label>
            <input type="file" name="image_file" id="image_file" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
            @error('image_file')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nome -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
        <!-- Botão -->
        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-md shadow text-sm">
                Create Category
            </button>
        </div>
    </form>
</div>
@endsection
