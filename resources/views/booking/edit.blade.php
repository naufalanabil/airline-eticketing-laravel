<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Data Penumpang</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-xl sm:px-6 lg:px-8">
            <div class="rounded-xl bg-white p-6 shadow-sm">
                <form action="{{ route('booking.update', $booking->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    @if ($errors->any())
                        <div class="rounded-lg bg-red-100 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @php($passenger = $booking->passengers->first())
                    <div>
                        <label for="full_name" class="mb-1 block text-sm font-semibold">Nama Lengkap Penumpang</label>
                        <input id="full_name" type="text" name="full_name" value="{{ old('full_name', $passenger?->full_name) }}" required class="w-full rounded-lg border border-gray-300 p-2.5">
                    </div>

                    <div>
                        <label for="passport_number" class="mb-1 block text-sm font-semibold">Nomor Paspor</label>
                        <input id="passport_number" type="text" name="passport_number" value="{{ old('passport_number', $passenger?->passport_number) }}" required class="w-full rounded-lg border border-gray-300 p-2.5">
                    </div>

                    <div>
                        <label for="passport_expiry" class="mb-1 block text-sm font-semibold">Masa Berlaku Paspor</label>
                        <input id="passport_expiry" type="date" name="passport_expiry" value="{{ old('passport_expiry', $passenger?->passport_expiry?->format('Y-m-d') ?? $passenger?->passport_expiry) }}" required class="w-full rounded-lg border border-gray-300 p-2.5">
                    </div>

                    <div>
                        <label for="nationality" class="mb-1 block text-sm font-semibold">Kewarganegaraan</label>
                        <input id="nationality" type="text" name="nationality" value="{{ old('nationality', $passenger?->nationality ?? 'Indonesia') }}" required class="w-full rounded-lg border border-gray-300 p-2.5">
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-600 underline hover:text-gray-900">Batal</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-700">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
