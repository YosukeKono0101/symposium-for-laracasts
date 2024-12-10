<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __("All Talks") }}
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
                    + Add New Talk
                </button>
            </div>
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($talks->isEmpty())
                        <p class="text-gray-500">
                            No talks available. Be the first to add one!
                        </p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach ($talks as $talk)
                                <li class="py-4">
                                    <a
                                        href="{{ route("talks.show", ["talk" => $talk]) }}"
                                        class="block text-lg font-medium text-blue-600 hover:underline"
                                    >
                                        {{ $talk->title }}
                                    </a>
                                    <p class="text-sm text-gray-500">
                                        {{ Str::limit($talk->abstract, 100) }}
                                    </p>
                                    <div class="mt-2 text-sm text-gray-400">
                                        <span>
                                            Type: {{ ucfirst($talk->type) }}
                                        </span>
                                        |
                                        <span>
                                            Length: {{ $talk->length }} minutes
                                        </span>
                                    </div>
                                    <p class="mt-2 text-sm text-gray-600">
                                        Organizer Notes:
                                        {{ Str::limit($talk->organizer_notes, 80) }}
                                    </p>
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
            <h2 class="mb-4 text-xl font-medium text-gray-700">Add New Talk</h2>
            <form method="POST" action="{{ route("talks.store") }}">
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
                        placeholder="How to save a life"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        value="{{ old("title") }}"
                        required
                    />
                    <x-input-error :messages="$errors->get('title')" />
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label
                            for="type"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Type
                        </label>
                        <select
                            id="type"
                            name="type"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                            required
                        >
                            @foreach (App\Enums\TalkType::cases() as $talkType)
                                <option
                                    {{ old("type") === $talkType->value ? "selected" : "" }}
                                    value="{{ $talkType->value }}"
                                >
                                    {{ ucwords($talkType->value) }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" />
                    </div>
                    <div class="flex-1">
                        <label
                            for="length"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Length
                        </label>
                        <input
                            type="text"
                            id="length"
                            name="length"
                            value="{{ old("length") }}"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        />
                        <x-input-error :messages="$errors->get('length')" />
                    </div>
                </div>
                <div class="mb-4">
                    <label
                        for="abstract"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Abstract
                    </label>
                    <textarea
                        id="abstract"
                        name="abstract"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        rows="5"
                    >
{{ old("abstract") }}</textarea
                    >
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Describe the talk in a few sentences, in a way that's
                        compelling and informative and could be presented to the
                        public.
                    </p>
                </div>
                <div class="mb-4">
                    <label
                        for="organizer_notes"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Organizer Notes
                    </label>
                    <textarea
                        id="organizer_notes"
                        name="organizer_notes"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        rows="5"
                    >
{{ old("organizer_notes") }}</textarea
                    >
                    <p class="mt-3 text-sm leading-6 text-gray-600">
                        Write any notes you may want to pass to an event
                        organizer about this talk.
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
    </script>
</x-app-layout>
