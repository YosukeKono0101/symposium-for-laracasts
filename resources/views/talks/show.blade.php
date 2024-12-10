<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a
                href="{{ route("talks.index") }}"
                class="flex items-center text-blue-600 hover:underline"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                Back
            </a>
            <h2 class="ml-4 text-xl font-semibold leading-tight text-gray-800">
                {{ $talk->title }}
            </h2>
        </div>
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
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-2xl font-semibold text-gray-800">
                        {{ $talk->title }}
                    </h3>

                    <p class="mb-4 text-gray-700">
                        <strong>Abstract:</strong>
                        {{ $talk->abstract ?? "No abstract provided." }}
                    </p>
                    <p class="mb-4 text-gray-700">
                        <strong>Type:</strong>
                        {{ ucfirst($talk->type) }}
                        <br />
                        <strong>Length:</strong>
                        {{ $talk->length ?? "Not specified" }} minutes
                    </p>
                    <p class="mb-6 text-gray-700">
                        <strong>Organizer Notes:</strong>
                        {{ $talk->organizer_notes ?? "No notes provided." }}
                    </p>

                    <div class="flex justify-end gap-4">
                        <button
                            type="button"
                            id="openEditModal"
                            class="rounded-md bg-gray-900 px-4 py-2 text-white hover:bg-gray-700"
                        >
                            Edit
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex justify-start">
                <button
                    type="button"
                    class="rounded-lg bg-red-700 px-2 py-1 text-white hover:bg-red-600"
                    onclick="confirmDelete('{{ route("talks.destroy", ["talk" => $talk]) }}')"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div
        id="editModal"
        class="fixed inset-0 flex hidden items-center justify-center bg-gray-800 bg-opacity-50 backdrop-blur-sm"
    >
        <div
            class="relative w-full max-w-2xl rounded-lg bg-white p-6 shadow-lg"
        >
            <button
                type="button"
                id="closeEditModal"
                class="absolute right-3 top-3 text-gray-500 hover:text-gray-700"
            >
                ✖
            </button>
            <h2 class="mb-4 text-xl font-medium text-gray-700">Edit Talk</h2>
            <form
                method="POST"
                action="{{ route("talks.update", ["talk" => $talk]) }}"
            >
                @csrf
                @method("PATCH")
                <div class="mb-4">
                    <label
                        for="editTitle"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Title
                    </label>
                    <input
                        type="text"
                        id="editTitle"
                        name="title"
                        value="{{ old("title", $talk->title) }}"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        required
                    />
                    <x-input-error :messages="$errors->get('title')" />
                </div>
                <div class="mb-4 flex gap-4">
                    <div class="flex-1">
                        <label
                            for="editType"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Type
                        </label>
                        <select
                            id="editType"
                            name="type"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                            required
                        >
                            @foreach (App\Enums\TalkType::cases() as $talkType)
                                <option
                                    {{ old("type", $talk->type) === $talkType->value ? "selected" : "" }}
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
                            for="editLength"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Length
                        </label>
                        <input
                            type="text"
                            id="editLength"
                            name="length"
                            value="{{ old("length", $talk->length) }}"
                            class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        />
                        <x-input-error :messages="$errors->get('length')" />
                    </div>
                </div>
                <div class="mb-4">
                    <label
                        for="editAbstract"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Abstract
                    </label>
                    <textarea
                        id="editAbstract"
                        name="abstract"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        rows="5"
                    >
{{ old("abstract", $talk->abstract) }}</textarea
                    >
                </div>
                <div class="mb-4">
                    <label
                        for="editOrganizerNotes"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Organizer Notes
                    </label>
                    <textarea
                        id="editOrganizerNotes"
                        name="organizer_notes"
                        class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                        rows="5"
                    >
{{ old("organizer_notes", $talk->organizer_notes) }}</textarea
                    >
                </div>
                <div class="flex justify-end gap-4">
                    <button
                        type="button"
                        id="closeEditModalCancel"
                        class="rounded-lg bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-gray-900 px-4 py-2 text-white hover:bg-gray-700"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(route) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = route;

                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);

                    form.submit();
                }
            });
        }

        const editModal = document.getElementById('editModal');
        const openEditModal = document.getElementById('openEditModal');
        const closeEditModal = document.getElementById('closeEditModal');
        const closeEditModalCancel = document.getElementById(
            'closeEditModalCancel',
        );

        openEditModal.addEventListener('click', () => {
            editModal.classList.remove('hidden');
        });

        closeEditModal.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        closeEditModalCancel.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });
    </script>
</x-app-layout>
