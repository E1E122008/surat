@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit Surat Masuk</h2>
                    </div>

                    <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="no_agenda" class="block text-sm font-medium text-gray-700">Nomor Agenda</label>
                                <input type="text" name="no_agenda" id="no_agenda" 
                                    class="form-control"
                                    value="{{ old('no_agenda', $suratMasuk->no_agenda) }}" required>
                                @error('no_agenda')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" 
                                    class="form-control"
                                    value="{{ old('no_surat', $suratMasuk->no_surat) }}" required>
                                @error('no_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pengirim" class="block text-sm font-medium text-gray-700">Pengirim</label>
                                <input type="text" name="pengirim" id="pengirim" 
                                    class="form-control"
                                    value="{{ old('pengirim', $suratMasuk->pengirim) }}" required>
                                @error('pengirim')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" id="tanggal_surat" 
                                    class="form-control"
                                    value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat ? $suratMasuk->tanggal_surat->format('Y-m-d') : '') }}" required>
                                @error('tanggal_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_terima" class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima" 
                                    class="form-control"
                                    value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima ? $suratMasuk->tanggal_terima->format('Y-m-d') : '') }}" required>
                                @error('tanggal_terima')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="perihal" class="block text-sm font-medium text-gray-700 mb-2">Perihal</label>
                                <textarea 
                                    name="perihal" 
                                    id="perihal" 
                                    class="form-textarea" 
                                    placeholder="Masukkan perihal surat" 
                                    required
                                >{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                                @error('perihal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <h6 class="font-semibold mb-2">
                                    Lampiran <span class="text-gray-400 text-sm">(PDF, DOC, DOCX, Gambar)</span>
                                </h6>
                                <div class="mb-3">
                                    <label class="block font-medium mb-1">Lampiran Lama:</label>
                                    <ul id="lampiran-lama-list">
                                        @forelse($lampiranLama as $idx => $file)
                                            @php
                                                $ext = isset($file['name']) ? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) : '';
                                            @endphp
                                            <li class="flex items-center justify-between py-1 group" id="lampiran-lama-{{ $idx }}">
                                                <div class="flex items-center">
                                                    {{-- Ikon file dinamis --}}
                                                    @if($ext === 'pdf')
                                                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7.828A2 2 0 0 0 15.414 7L13 4.586A2 2 0 0 0 11.586 4H6z"/></svg>
                                                    @elseif(in_array($ext, ['doc', 'docx']))
                                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7.828A2 2 0 0 0 15.414 7L13 4.586A2 2 0 0 0 11.586 4H6z"/></svg>
                                                    @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><rect width="20" height="20" rx="3"/><circle cx="7" cy="8" r="2" fill="white"/><path d="M2 16l5-6 4 5 3-4 4 5" stroke="white" stroke-width="1.5" fill="none"/></svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20"><rect width="20" height="20" rx="3"/></svg>
                                                    @endif
                                                    <a href="{{ asset('storage/' . $file['path']) }}"
                                                       class="text-blue-600 hover:text-blue-800 transition-colors duration-150"
                                                       target="_blank">{{ $file['name'] }}</a>
                                                </div>
                                                {{-- Tombol hapus dengan SweetAlert --}}
                                                <button type="button"
                                                    class="ml-2 p-1 rounded-full hover:bg-red-100 transition"
                                                    style="line-height: 0;"
                                                    onclick="confirmDeleteLampiran('{{ $idx }}', '{{ $file['name'] }}')"
                                                    title="Hapus lampiran">
                                                    <svg class="w-4 h-4 text-red-500 hover:text-red-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 20 20">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l8 8M6 14L14 6"/>
                                                    </svg>
                                                </button>
                                                {{-- Hidden input untuk mempertahankan lampiran lama saat submit --}}
                                                <input type="hidden" name="lampiran_lama[]" value="{{ $file['path'] }}">
                                            </li>
                                        @empty
                                            <li class="text-gray-400">Tidak ada lampiran lama.</li>
                                        @endforelse
                                    </ul>
                                </div>
                                <div class="mb-3">
                                    <label class="block font-medium mb-1">Tambah Lampiran Baru</label>
                                    <input type="file" name="lampiran[]" multiple class="block w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
                                    <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin menambah file baru</p>
                                </div>
                            </div>
                        </div>

                        <div class="button-container">
                            <a href="{{ route('surat-masuk.detail', $suratMasuk->id) }}" 
                                class="btn-cancel">
                                Batal
                            </a>
                            <button type="submit" 
                                class="btn-update">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert Script --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        // Fungsi untuk konfirmasi hapus lampiran dengan SweetAlert
        function confirmDeleteLampiran(idx, fileName) {
            swal({
                title: 'Apakah Anda yakin?',
                text: `File "${fileName}" akan dihapus dari lampiran!`,
                icon: 'warning',
                buttons: {
                    cancel: {
                        text: "Batal",
                        value: null,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Ya, hapus!",
                        value: true,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: true
                    }
                },
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    // Hapus elemen dari DOM
                    const element = document.getElementById('lampiran-lama-' + idx);
                    if (element) {
                        element.remove();
                    }
                    
                    swal("File berhasil dihapus dari lampiran!", {
                        icon: "success",
                        timer: 2000
                    });
                }
            });
        }
    </script>
@endsection