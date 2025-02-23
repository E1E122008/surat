@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Detail Surat Masuk</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-group mb-3">
                        <label for="no_agenda">Nomor Agenda</label>
                        <input type="text" name="no_agenda" id="no_agenda" class="form-control border-effect" value="{{ $surat->no_agenda }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_surat">Nomor Surat</label>
                        <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $surat->no_surat }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="pengirim">Pengirim</label>
                        <input type="text" name="pengirim" id="pengirim" class="form-control border-effect" value="{{ $surat->pengirim }}" readonly>
                    </div> 
                </div> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-3">
                        <label for="tanggal_surat">Tanggal Surat</label>
                        <input type="text" name="tanggal_surat" id="tanggal_surat" class="form-control border-effect" value="{{ $surat->tanggal_surat->format('d/m/Y') }}" readonly>
                    </div>
                        
                    <div class=" form-group mb-3">
                        <label for="tanggal_terima">Tanggal Terima</label>
                        <input type="text" name="tanggal_terima" id="tanggal_terima" class="form-control border-effect" value="{{ $surat->tanggal_terima->format('d/m/Y') }}" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-3">
                        <label for="perihal">Perihal</label>
                        <textarea name="perihal" id="perihal" class="form-control border-effect" readonly>{{ $surat->perihal }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="catatan">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control border-effect" readonly>{{ $surat->catatan }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="disposisi">Disposisi</label>
                        <input type="text" name="disposisi" id="disposisi" class="form-control border-effect" value="{{ $surat->disposisi }}" readonly>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="status">Status</label>
                        <input type="text" name="status" id="status" class="form-control border-effect" value="{{ $surat->status }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="lampiran">Lampiran</label>
                        <button onclick="window.location.href='{{ asset('storage/' . $surat->lampiran) }}'" class="btn btn-primary">
                            <i class="fas fa-file-pdf"></i> {{ basename($surat->lampiran) }}
                        </button>
                    </div>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="button-container flex space-x-4">
                        <a href="{{ route('surat-masuk.index') }}" class="btn-cancel flex items-center justify-center h-12 w-32 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="btn btn-info flex items-center justify-center h-12 w-32 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition duration-200">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    </div>
                </div>
                

            </div>
        </div>
    </div>
@endsection
