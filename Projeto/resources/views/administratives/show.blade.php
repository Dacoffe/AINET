<x-layouts.main-content :title="$administrative->name"
                        heading="View Administrative"
                        :subheading="$administrative->name">

<div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('administratives.create', ['administrative' => $administrative]) }}">New</flux:button>
                    <flux:button href="{{ route('administratives.edit', ['administrative' => $administrative]) }}">Edit</flux:button>
                    <form method="POST" action="{{ route('administratives.destroy', ['administrative' => $administrative]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>
                <div class="mt-6 space-y-4">
                    @include('administratives.partials.fields', ['mode' => 'show'])
                </div>
            </section>
        </div>
    </div>
</x-layouts.main-content>
