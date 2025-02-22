@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Detail Surat Masuk</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group mb-3">
                            <label for="no_agenda">Nomor Agenda</label>
                            <input type="text" name="no_agenda" id="no_agenda" class="form-control border-effect" value="{{ $sk->no_agenda }}" readonly>
                        </div>

                        <div class="form-group mb-3">
                            <label for="no_surat">Nomor Surat</label>
                            <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $sk->no_surat }}" readonly>
                        </div>  

                        <div class="form-group mb-3">
                            <label for="pengirim">Pengirim</label>
                            <input type="text" name="pengirim" id="pengirim" class="form-control border-effect" value="{{ $sk->pengirim }}" readonly>
                        </div>    

                        <div class="form-group mb-3">
                            <label for="tanggal_surat">Tanggal Surat</label>
                            <input type="text" name="tanggal_surat" id="tanggal_surat" class="form-control border-effect" value="{{ $sk->tanggal_surat->format('d/m/Y') }}" readonly>
                        </div>  

                        <div class="form-group mb-3">
                            <label for="tanggal_terima">Tanggal Terima</label>
                            <input type="text" name="tanggal_terima" id="tanggal_terima" class="form-control border-effect" value="{{ $sk->tanggal_terima->format('d/m/Y') }}" readonly>
                        </div>  

                        <div class="form-group mb-3">
                            <label for="perihal">Perihal</label>
                            <textarea name="perihal" id="perihal" class="form-control border-effect" readonly>{{ $sk->perihal }}</textarea>
                        </div>  

                        <div class="form-group mb-3">
                            <label for="catatan">Catatan</label>
                            <textarea name="catatan" id="catatan" class="form-control border-effect" readonly>{{ $sk->catatan }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="disposisi">Disposisi</label>
                            <input type="text" name="disposisi" id="disposisi" class="form-control border-effect" value="{{ $sk->disposisi }}" readonly>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="lampiran">Lampiran</label>
                            <button onclick="window.location.href='{{ asset('storage/' . $sk->lampiran) }}'" class="btn btn-primary">
                                <i class="fas fa-eye"></i> {{ basename($sk->lampiran) }}
                            </button>
                        </div>  
                        
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="button-container">
                                <a href="{{ route('draft-phd.sk.index') }}" class="btn-cancel">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
@endsection
