<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __("All Conferences") }}
        </h2>
    </x-slot>

    @if (session("status"))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => (show = false), 3000)"
            x-show="show"
            x-transition:leave="transition duration-500 ease-in"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="mx-auto max-w-md sm:px-6 lg:px-8"
        >
            <div
                class="mb-6 rounded-lg bg-green-100 p-4 text-center text-green-800 shadow-lg"
            >
                {{ session("status") }}
            </div>
        </div>
    @endif

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-end">
                <button
                    type="button"
                    id="openModal"
                    class="rounded-md bg-gray-900 px-2 py-2 text-white hover:bg-gray-700"
                >
                    + Add New Conference
                </button>
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($conferences->isEmpty())
                        <p class="text-gray-500">
                            No conference available. Be the first to add one!
                        </p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($conferences as $conference)
                                <li
                                    class="flex items-center justify-between py-4"
                                >
                                    <div>
                                        <a
                                            href="{{ route("conferences.show", ["conference" => $conference]) }}"
                                            class="block text-lg font-medium text-blue-600 hover:underline"
                                        >
                                            {{ $conference->title }}
                                        </a>
                                        <p class="text-sm text-gray-500">
                                            {{ Str::limit($conference->description, 100) }}
                                        </p>
                                        <div class="mt-2 text-sm text-gray-400">
                                            <span>
                                                Location:
                                                {{ $conference->location }}
                                            </span>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">
                                            Link: {{ $conference->url }}
                                        </p>
                                    </div>
                                    <div
                                        x-data="{
                                            isFavorited:
                                                {{ Auth::user()->favoritedConferences->pluck("id")->contains($conference->id) ? "true" : "false" }},
                                            animate: false,
                                        }"
                                    >
                                        <button
                                            @click="
                                                animate = true;
                                                toggleFavorite({{ $conference->id }}, isFavorited);
                                                isFavorited = !isFavorited;
                                                setTimeout(() => animate = false, 500);
                                            "
                                            class="text-2xl transition-transform duration-300 ease-in-out"
                                            :class="{
                                                'text-yellow-500': isFavorited,
                                                'text-gray-400': !isFavorited,
                                                'scale-125': animate
                                            }"
                                            title="Toggle Favorite"
                                        >
                                            <span
                                                x-text="isFavorited ? '★' : '☆'"
                                            ></span>
                                        </button>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div
        id="modal"
        class="fixed inset-0 flex hidden items-center justify-center bg-gray-800 bg-opacity-50 backdrop-blur-sm"
    >
        <div
            class="relative w-full max-w-2xl rounded-lg bg-white p-6 shadow-lg"
        >
            <button
                type="button"
                id="closeModal"
                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
            >
                ✖
            </button>
            <h2 class="mb-4 text-xl font-medium text-gray-700">
                Add New conference
            </h2>
            <form method="POST" action="{{ route("conferences.store") }}">
                @csrf
                <div class="mb-4">
                    <label
                        for="title"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Title
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        placeholder="How to be a better developer"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        value="{{ old("title") }}"
                        required
                    />
                    <x-input-error :messages="$errors->get('title')" />
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label
                            for="location"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Location
                        </label>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old("location") }}"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                            required
                        />
                        <x-input-error :messages="$errors->get('location')" />
                    </div>
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label
                            for="starts_at"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Starts At
                        </label>
                        <input
                            type="date"
                            id="starts_at"
                            name="starts_at"
                            value="{{ old("starts_at", \Carbon\Carbon::parse($conference->starts_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                            required
                        />
                        <x-input-error :messages="$errors->get('starts_at')" />
                    </div>
                    <div class="flex-1">
                        <label
                            for="end_at"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Ends At
                        </label>
                        <input
                            type="date"
                            id="ends_at"
                            name="ends_at"
                            value="{{ old("ends_at", \Carbon\Carbon::parse($conference->ends_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                            required
                        />
                        <x-input-error :messages="$errors->get('ends_at')" />
                    </div>
                </div>
                <div class="mb-4">
                    <label
                        for="abstract"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        rows="5"
                    >
{{ old("description") }}</textarea
                    >
                    <p class="mt-1 text-sm leading-6 text-gray-600">
                        Describe the conference in a few sentences, in a way
                        that's compelling and informative and could be presented
                        to the public.
                    </p>
                </div>
                <div class="mb-4">
                    <label
                        for="organizer_notes"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        URL
                    </label>
                    <input
                        type="text"
                        id="url"
                        name="url"
                        value="{{ old("url") }}"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                    />
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Provide a link to the conference website.
                    </p>
                </div>
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        id="closeModalCancel"
                        class="rounded-lg bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-white hover:bg-gray-700"
                    >
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');
        const openModal = document.getElementById('openModal');
        const closeModal = document.getElementById('closeModal');
        const closeModalCancel = document.getElementById('closeModalCancel');

        openModal.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        closeModalCancel.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        function toggleFavorite(conferenceId, isFavorited) {
            const method = isFavorited ? 'DELETE' : 'POST';
            fetch(`/conferences/${conferenceId}/favorite`, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content'),
                },
            }).catch(() => {
                alert('Something went wrong!');
            });
        }
    </script>
</x-app-layout>
