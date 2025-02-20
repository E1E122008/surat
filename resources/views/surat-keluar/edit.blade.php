@extends('layouts.app')

@section('content')
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <div class="mb-6">
                <h2 class="text-2xl font-semibold">Edit Surat Keluar</h2>
            </div>

            <form action="{{ route('surat-keluar.update', $suratKeluar->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 flex flex-col md:flex-row md:space-x-4">
                        <div class="flex-1">
                            <label for="no_agenda" class="form-label">Nomor Agenda</label>
                            <input type="text" name="no_agenda" id="no_agenda" 
                                class="form-control @error('no_agenda') is-invalid @enderror"
                                value="{{ old('no_agenda', $suratKeluar->no_agenda) }}" required>
                            @error('no_agenda')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror   
                        </div>

                        <div class="flex-1">
                            <label for="no_surat" class="form-label">Nomor Surat</label>
                            <input type="text" name="no_surat" id="no_surat" 
                                class="form-control @error('no_surat') is-invalid @enderror"
                                value="{{ old('no_surat', $suratKeluar->no_surat) }}" required>
                            @error('no_surat')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex-1">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" id="tanggal_surat" 
                            class="form-control @error('tanggal_surat') is-invalid @enderror"
                            value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat ? $suratKeluar->tanggal_surat->format('Y-m-d') : '') }}" required>
                        @error('tanggal_surat')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-span-2">
                        <label for="perihal" class="block text-sm font-medium text-gray-700">Perihal</label>
                        <textarea name="perihal" id="perihal" 
                            class="form-textarea" 
                            placeholder="Masukkan perihal surat" 
                            required
                        >{{ old('perihal', $suratKeluar->perihal) }}</textarea>
                        @error('perihal')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror   
                    </div>

                    <div class="md:col-span-2">
                        <label for="lampiran" class="form-label">Lampiran</label>
                        @if($suratKeluar->lampiran)
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">File saat ini: {{ basename($suratKeluar->lampiran) }}</p>
                            </div>
                        @endif
                        <input type="file" name="lampiran" id="lampiran" 
                            class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                            @error('lampiran') is-invalid @enderror">   
                        @error('lampiran')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>  

                <div class="button-container">
                    <a href="{{ route('surat-keluar.index') }}" 
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
@endsection

