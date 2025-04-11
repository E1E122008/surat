<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">{{ $request->title }}</h3>
                        <p class="mt-1 text-sm text-gray-500">Jenis Data: 
                            @if($request->data_type == 'surat_masuk') Surat Masuk
                            @elseif($request->data_type == 'surat_keluar') Surat Keluar
                            @else Disposisi @endif
                        </p>
                    </div>

                    @if($request->data_type == 'surat_masuk')
                    <form method="POST" action="{{ route('surat-masuk.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="request_id" value="{{ $request->id }}">

                        <div>
                            <x-input-label for="nomor_surat" :value="__('Nomor Surat')" />
                            <x-text-input id="nomor_surat" name="nomor_surat" type="text" class="mt-1 block w-full" :value="old('nomor_surat')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_surat')" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_surat" :value="__('Tanggal Surat')" />
                            <x-text-input id="tanggal_surat" name="tanggal_surat" type="date" class="mt-1 block w-full" :value="old('tanggal_surat')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tanggal_surat')" />
                        </div>

                        <div>
                            <x-input-label for="pengirim" :value="__('Pengirim')" />
                            <x-text-input id="pengirim" name="pengirim" type="text" class="mt-1 block w-full" :value="old('pengirim')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('pengirim')" />
                        </div>

                        <div>
                            <x-input-label for="perihal" :value="__('Perihal')" />
                            <x-text-input id="perihal" name="perihal" type="text" class="mt-1 block w-full" :value="old('perihal')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('perihal')" />
                        </div>

                        <div>
                            <x-input-label for="file" :value="__('File Surat')" />
                            <x-text-input id="file" name="file" type="file" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @elseif($request->data_type == 'surat_keluar')
                    <form method="POST" action="{{ route('surat-keluar.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="request_id" value="{{ $request->id }}">

                        <div>
                            <x-input-label for="nomor_surat" :value="__('Nomor Surat')" />
                            <x-text-input id="nomor_surat" name="nomor_surat" type="text" class="mt-1 block w-full" :value="old('nomor_surat')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nomor_surat')" />
                        </div>

                        <div>
                            <x-input-label for="tanggal_surat" :value="__('Tanggal Surat')" />
                            <x-text-input id="tanggal_surat" name="tanggal_surat" type="date" class="mt-1 block w-full" :value="old('tanggal_surat')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tanggal_surat')" />
                        </div>

                        <div>
                            <x-input-label for="tujuan" :value="__('Tujuan')" />
                            <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full" :value="old('tujuan')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tujuan')" />
                        </div>

                        <div>
                            <x-input-label for="perihal" :value="__('Perihal')" />
                            <x-text-input id="perihal" name="perihal" type="text" class="mt-1 block w-full" :value="old('perihal')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('perihal')" />
                        </div>

                        <div>
                            <x-input-label for="file" :value="__('File Surat')" />
                            <x-text-input id="file" name="file" type="file" class="mt-1 block w-full" required />
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @else
                    <form method="POST" action="{{ route('disposisi.store') }}" class="space-y-6">
                        @csrf
                        <input type="hidden" name="request_id" value="{{ $request->id }}">

                        <div>
                            <x-input-label for="surat_masuk_id" :value="__('Surat Masuk')" />
                            <select id="surat_masuk_id" name="surat_masuk_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Pilih Surat Masuk</option>
                                @foreach($suratMasuk as $surat)
                                    <option value="{{ $surat->id }}">{{ $surat->nomor_surat }} - {{ $surat->perihal }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('surat_masuk_id')" />
                        </div>

                        <div>
                            <x-input-label for="tujuan" :value="__('Tujuan Disposisi')" />
                            <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full" :value="old('tujuan')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('tujuan')" />
                        </div>

                        <div>
                            <x-input-label for="isi" :value="__('Isi Disposisi')" />
                            <textarea id="isi" name="isi" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('isi') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('isi')" />
                        </div>

                        <div>
                            <x-input-label for="batas_waktu" :value="__('Batas Waktu')" />
                            <x-text-input id="batas_waktu" name="batas_waktu" type="date" class="mt-1 block w-full" :value="old('batas_waktu')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('batas_waktu')" />
                        </div>

                        <div class="flex items-center justify-end">
                            <x-primary-button class="ml-4">
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 