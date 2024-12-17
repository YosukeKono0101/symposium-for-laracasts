<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a
                href="{{ route("conferences.index") }}"
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
                {{ $conference->title }}
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
                    <div class="flex items-center justify-between">
                        <h3 class="mb-4 text-2xl font-semibold text-gray-800">
                            {{ $conference->title }}
                        </h3>
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
                                class="text-3xl transition-transform duration-300 ease-in-out"
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
                    </div>

                    <p class="mb-4 text-gray-700">
                        <strong>Description:</strong>
                        {{ $conference->description ?? "No description provided." }}
                    </p>
                    <p class="mb-4 text-gray-700">
                        <strong>Location:</strong>
                        {{ $conference->location ?? "No location provided." }}
                    </p>
                    <p class="mb-4 text-gray-700">
                        <strong>Starts At:</strong>
                        {{ $conference->starts_at ?? "No start date provided." }}
                        |
                        <strong>Ends At:</strong>
                        {{ $conference->ends_at ?? "No end date provided." }}
                    </p>
                    <p class="mb-4 text-gray-700">
                        <strong>CFP Starts At:</strong>
                        {{ $conference->cfp_starts_at ?? "No CFP start date provided." }}
                        |
                        <strong>CFP Ends At:</strong>
                        {{ $conference->cfp_ends_at ?? "No CFP end date provided." }}
                    </p>
                    <p class="mb-6 text-gray-700">
                        <strong>URL:</strong>
                        <a
                            href="{{ $conference->url }}"
                            class="text-blue-600 hover:underline"
                        >
                            {{ $conference->url }}
                        </a>
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
                    onclick="confirmDelete('{{ route("conferences.destroy", ["conference" => $conference]) }}')"
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
            class="relative w-full max-w-3xl rounded-lg bg-white p-8 shadow-lg"
        >
            <button
                type="button"
                id="closeEditModal"
                class="absolute right-3 top-4 text-gray-500 hover:text-gray-700"
            >
                ✖
            </button>
            <h2 class="mb-6 text-xl font-semibold text-gray-800">
                Edit Conference
            </h2>
            <form
                method="POST"
                action="{{ route("conferences.update", ["conference" => $conference]) }}"
            >
                @csrf
                @method("PATCH")
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
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
                            value="{{ old("title", $conference->title) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                        <x-input-error :messages="$errors->get('title')" />
                    </div>
                    <div>
                        <label
                            for="editLocation"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Location
                        </label>
                        <input
                            type="text"
                            id="editLocation"
                            name="location"
                            value="{{ old("location", $conference->location) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                        <x-input-error :messages="$errors->get('location')" />
                    </div>
                    <div>
                        <label
                            for="editStartsAt"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Starts At
                        </label>
                        <input
                            type="date"
                            id="editStartsAt"
                            name="starts_at"
                            value="{{ old("starts_at", \Carbon\Carbon::parse($conference->starts_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="editEndsAt"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            Ends At
                        </label>
                        <input
                            type="date"
                            id="editEndsAt"
                            name="ends_at"
                            value="{{ old("ends_at", \Carbon\Carbon::parse($conference->ends_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="editCfpStartsAt"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            CFP Starts At
                        </label>
                        <input
                            type="date"
                            id="editCfpStartsAt"
                            name="cfp_starts_at"
                            value="{{ old("cfp_starts_at", \Carbon\Carbon::parse($conference->cfp_starts_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>
                    <div>
                        <label
                            for="editCfpEndsAt"
                            class="mb-2 block font-medium text-gray-700"
                        >
                            CFP Ends At
                        </label>
                        <input
                            type="date"
                            id="editCfpEndsAt"
                            name="cfp_ends_at"
                            value="{{ old("cfp_ends_at", \Carbon\Carbon::parse($conference->cfp_ends_at)->format("Y-m-d")) }}"
                            class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                            required
                        />
                    </div>
                </div>
                <div class="mt-4">
                    <label
                        for="editDescription"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        Description
                    </label>
                    <textarea
                        id="editDescription"
                        name="description"
                        rows="4"
                        class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                    >
{{ old("description", $conference->description) }}</textarea
                    >
                </div>
                <div class="mt-4">
                    <label
                        for="editUrl"
                        class="mb-2 block font-medium text-gray-700"
                    >
                        URL
                    </label>
                    <input
                        type="text"
                        id="editUrl"
                        name="url"
                        value="{{ old("url", $conference->url) }}"
                        class="w-full rounded-lg border-gray-300 p-2 focus:border-blue-500 focus:ring-blue-500"
                    />
                </div>
                <div class="mt-6 flex justify-end gap-4">
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
                alert(
                    'Something went wrong while updating your favorite status.',
                );
            });
        }

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
