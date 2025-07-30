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
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">Lampiran (PDF, DOC, DOCX, Gambar)</label>
                                
                                @if($suratMasuk->lampiran)
                                    @php
                                        $lampiranData = json_decode($suratMasuk->lampiran, true) ?? [];
                                    @endphp
                                    
                                    @if(is_array($lampiranData) && count($lampiranData) > 0)
                                        <div class="mt-3 space-y-2">
                                            <h4 class="text-sm font-medium text-gray-700">File Lampiran Saat Ini:</h4>
                                            @foreach($lampiranData as $index => $file)
                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $file['name'] ?? 'File' }}</p>
                                                            <p class="text-xs text-gray-500">{{ basename($file['path'] ?? '') }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        @if(isset($file['path']))
                                                            <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" 
                                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                                Lihat
                                                            </a>
                                                        @endif
                                                        <label class="flex items-center">
                                                            <input type="checkbox" name="hapus_lampiran[]" value="{{ $index }}" 
                                                                   class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                                            <span class="ml-2 text-sm text-red-600">Hapus</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tambah File Baru (Opsional)</label>
                                    <input type="file" name="lampiran[]" id="lampiran" 
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100"
                                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                    <p class="mt-1 text-sm text-gray-500">Pilih file untuk ditambahkan ke lampiran existing</p>
                                </div>
                                
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('lampiran.*')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
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
@endsection