@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit SPT Luar Daerah</h2>
                    </div>

                    <form action="{{ route('spt-luar-daerah.update', $sptLuarDaerah->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $sptLuarDaerah->no_surat }}" >
                            </div>

                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" 
                                class="form-control @error('tanggal') is-invalid @enderror"
                                value="{{ old('tanggal', $sptLuarDaerah->tanggal->format('Y-m-d')) }}" required>
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="tujuan" class="block text-sm font-medium text-gray-700">Tujuan</label>
                                <input type="text" name="tujuan" id="tujuan" class="form-control border-effect" value="{{ $sptLuarDaerah->tujuan }}" >
                            </div>
                            
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group mb-3">
                                <label for="perihal" class="form-label">Perihal</label>
                                <textarea name="perihal" id="perihal" rows="2" class="form-textarea" placeholder="Masukkan perihal surat" required>{{ old('perihal', $sptLuarDaerah->perihal) }}</textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                                <textarea name="nama_petugas" id="nama_petugas" rows="2" class="form-textarea" placeholder="Masukkan nama petugas" required>{{ old('nama_petugas', $sptLuarDaerah->nama_petugas) }}</textarea>
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700 mb-2">Lampiran (PDF, DOC, DOCX, Gambar)</label>
                                @if($sptLuarDaerah->lampiran)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">File saat ini: {{ basename($sptLuarDaerah->lampiran) }}</p>
                                    </div>
                                @endif
                                <input type="file" name="lampiran" id="lampiran" class="mt-1 block w-full text-sm text-gray-500 
                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah file</p>
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="button-container">
                            <a href="{{ route('spt-luar-daerah.detail', $sptLuarDaerah->id) }}" class="btn-cancel">
                                Batal
                            </a>
                            <button type="submit" class="btn-update">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection




