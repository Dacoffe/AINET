@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 lg:max-w-7xl lg:px-8">

    <div class="bg-blue-900 rounded-t-lg px-6 py-4">
        <h2 class="text-xl font-bold text-white">Categories</h2>

    </div>

    @include('partials.admin.menu-choice')
        <div class="mt-6">
            <a href="{{ route('categories.create') }}"
                class="inline-flex items-center px-4 py-4 bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-4 transition ease-in-out duration-150">
                Create New Category
            </a>
        </div>
        <x-categories.category-table :categories="$categories"
                                  :showView="true"
                                  :showEdit="true"
                                  :showDelete="true"
        />

        <!-- Paginação -->
        <div class="mt-8">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
