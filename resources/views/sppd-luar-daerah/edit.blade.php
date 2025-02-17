@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit SPPD Luar Daerah</h2>
                    </div>

                    <form action="{{ route('sppd-luar-daerah.update', $sppdLuarDaerah->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-grid grid grid-cols-1 md:grid-cols-2 gap-6 ">
                            <div class="form-group">
                                <label for="no_agenda" class="form-label">Nomor Agenda</label>
                                <input type="text" name="no_agenda" id="no_agenda" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('no_agenda', $sppdLuarDaerah->no_agenda) }}" required>
                                @error('no_agenda')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="no_surat" class="form-label">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('no_surat', $sppdLuarDaerah->no_surat) }}" required>
                                @error('no_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>      

                            <div class="form-group">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" id="tanggal" 
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                    value="{{ old('tanggal', $sppdLuarDaerah->tanggal ? $sppdLuarDaerah->tanggal->format('Y-m-d') : '') }}" required>
                                @error('tanggal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tujuan" class="form-label">Tujuan</label>
                                <input type="text" name="tujuan" id="tujuan" 
                                    class="form-control @error('tujuan') is-invalid @enderror"
                                    value="{{ old('tujuan', $sppdLuarDaerah->tujuan) }}" required>
                                @error('tujuan')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="perihal" class="form-label">Perihal</label>
                                <textarea name="perihal" id="perihal" 
                                    class="form-textarea" 
                                    placeholder="Masukkan perihal surat" 
                                    required
                                >{{ old('perihal', $sppdLuarDaerah->perihal) }}</textarea>
                                @error('perihal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                                <textarea name="nama_petugas" id="nama_petugas" 
                                    class="form-textarea" 
                                    placeholder="Masukkan nama petugas" 
                                    required
                                >{{ old('nama_petugas', $sppdLuarDaerah->nama_petugas) }}</textarea>
                                @error('nama_petugas')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">    
                                <label for="lampiran" class="form-label">Lampiran</label>   
                                @if($sppdLuarDaerah->lampiran)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">File saat ini: {{ basename($sppdLuarDaerah->lampiran) }}</p>
                                    </div>
                                @endif
                                <input type="file" name="lampiran" id="lampiran" 
                                    class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah file</p>     
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="button-container">
                            <a href="{{ route('sppd-luar-daerah.index') }}" 
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
@endsection

