<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $talk->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ $talk->title }}
                    <x-delete-item
                        :route="route('talks.destroy', ['talk' => $talk])"
                        text="Delete this talk"
                    />
                    <br />
                    <a
                        href="{{ route("talks.edit", ["talk" => $talk]) }}"
                        class="hover:underline"
                    >
                        Edit this talk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
