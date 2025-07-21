<x-layouts.main-content :title="$administrative->name"
                        heading="Edit Administrative"
                        :subheading="$administrative->name">
    <div class="flex flex-col space-y-6">
        <div class="max-full">
            <section>
                <div class="static sm:absolute -top-2 right-0 flex flex-wrap justify-start sm:justify-end items-center gap-4">
                    <flux:button variant="primary" href="{{ route('administratives.create', ['administrative' => $administrative]) }}">New</flux:button>
                    <flux:button href="{{ route('administratives.show', ['administrative' => $administrative]) }}">View</flux:button>
                    <form method="POST" action="{{ route('administratives.destroy', ['administrative' => $administrative]) }}">
                        @csrf
                        @method('DELETE')
                        <flux:button variant="danger" type="submit">Delete</flux:button>
                    </form>
                </div>

                <form method="POST" action="{{ route('administratives.update', ['administrative' => $administrative]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mt-6 space-y-4">
                        @include('administratives.partials.fields', ['mode' => 'edit'])
                    </div>
                    <div class="flex mt-6">
                        <flux:button variant="primary" type="submit"  class="uppercase">Save</flux:button>
                        <flux:button class="uppercase ms-4" href="{{ url()->full() }}">Cancel</flux:button>
                    </div>
                </form>
            </section>
        </div>
    </div>
    <form class="hidden" id="form_to_delete_photo"
        method="POST" 
        action="{{ route('administratives.photo.destroy', ['administrative' => $administrative]) }}">
        @csrf
        @method('DELETE')
    </form>    
</x-layouts.main-content>
