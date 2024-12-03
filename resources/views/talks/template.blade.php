<div class="mx-auto my-8 max-w-2xl rounded-lg bg-white px-8 py-6">
    <form method="POST" action="{{ route("talks.store") }}">
        @csrf
        <div class="mb-4">
            <label for="title" class="mb-2 block font-medium text-gray-700">
                Title
            </label>
            <input
                type="text"
                id="title"
                name="title"
                placeholder="How to save a life"
                class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                required
            />
        </div>
        <div class="mb-4 flex gap-4">
            <div class="flex-1">
                <label for="type" class="mb-2 block font-medium text-gray-700">
                    Type
                </label>
                <select
                    id="type"
                    name="type"
                    class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                    required
                >
                    <option value="">Select type</option>
                    <option value="standard">Standard</option>
                    <option value="lightning">Lightning</option>
                    <option value="keynote">Keynote</option>
                </select>
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
                    class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                />
                <x-input-error :messages="$errors->get('length')" />
            </div>
        </div>
        <div class="mb-4">
            <label for="abstract" class="mb-2 block font-medium text-gray-700">
                Abstract
            </label>
            <textarea
                id="abstract"
                name="abstract"
                class="w-full rounded-lg border border-gray-400 p-2 focus:border-blue-400 focus:outline-none"
                rows="5"
            ></textarea>
            <p class="mt-3 text-sm leading-6 text-gray-600">
                Describe the talk in a few sentences, in a way tha's compelling
                and informative and could be presented to the public.
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
            ></textarea>
            <p class="mt-3 text-sm leading-6 text-gray-600">
                Write any notes you may want to pass to an event organizer about
                this talk.
            </p>
        </div>
        <div class="flex justify-end gap-4">
            <button
                type="button"
                class="rounded-lg bg-gray-500 px-4 py-2 text-white hover:bg-gray-600"
            >
                Cancel
            </button>
            <button
                type="submit"
                class="rounded-lg bg-gray-800 px-4 py-2 text-white hover:bg-gray-900"
            >
                Submit
            </button>
        </div>
    </form>
</div>
