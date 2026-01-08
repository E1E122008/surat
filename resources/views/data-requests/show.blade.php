<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Permintaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Detail Permintaan Surat</h3>
                        <p class="mt-1 text-sm text-gray-500">Dibuat oleh {{ $request->user->name }} pada {{ $request->created_at->format('d M Y H:i') }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Jenis Surat</h4>
                            <p class="mt-1 text-sm text-gray-900">
                                @php
                                    $letterTypes = [
                                        'surat_masuk' => 'Surat Masuk',
                                        'sk' => 'SK',
                                        'perda' => 'PERDA',
                                        'pergub' => 'PERGUB',
                                    ];
                                @endphp
                                {{ $letterTypes[$request->letter_type] ?? $request->letter_type }}
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">No. Surat</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->no_surat ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Perihal</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->perihal ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Tanggal Surat</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->tanggal_surat ? $request->tanggal_surat->format('d M Y') : '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Status</h4>
                            <p class="mt-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'approved') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800 @endif">
                                    @if($request->status == 'pending') Menunggu Persetujuan
                                    @elseif($request->status == 'approved') Disetujui
                                    @else Ditolak @endif
                                </span>
                            </p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">No. HP</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->no_hp ?? '-' }}</p>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Catatan</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->notes ?? '-' }}</p>
                        </div>

                        @if($request->status != 'pending')
                        <div class="md:col-span-2">
                            <h4 class="text-sm font-medium text-gray-500">Catatan Admin</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $request->admin_notes ?? '-' }}</p>
                        </div>
                        @endif
                    </div>

                    @if(auth()->user()->role == 'admin' && $request->status == 'pending')
                    <div class="mt-8">
                        <form method="POST" action="{{ route('data-requests.update', $request->id) }}" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="approved">Setujui</option>
                                    <option value="rejected">Tolak</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            <div>
                                <x-input-label for="admin_notes" :value="__('Catatan')" />
                                <textarea id="admin_notes" name="admin_notes" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('admin_notes') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('admin_notes')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <x-primary-button class="ml-4">
                                    {{ __('Simpan') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                    @endif

                    @if($request->status == 'approved' && auth()->user()->id == $request->user_id)
                    <div class="mt-8">
                        <a href="{{ route('data-requests.edit', $request->id) }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Tambah Data
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 