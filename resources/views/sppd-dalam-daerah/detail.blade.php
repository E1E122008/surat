@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Detail SPPD Dalam Daerah</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                        <div class="form-group">
                            <label for="no_surat" class="font-semibold">Nomor Surat</label>
                            <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $sppd->no_surat }}" readonly>
                        </div>
                    
                        <div class="form-group">
                            <label for="tanggal" class="font-semibold">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control border-effect" value="{{ $sppd->tanggal->format('d/m/Y') }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label for="tujuan" class="font-semibold">Tujuan</label>
                            <input type="text" name="tujuan" id="tujuan" class="form-control border-effect" value="{{ $sppd->tujuan }}" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="perihal" class="font-semibold">Perihal</label>
                            <textarea name="perihal" id="perihal" class="form-control border-effect" readonly rows="4">{{ $sppd->perihal }}</textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="nama_petugas" class="font-semibold" >Nama yang di Tugaskan</label>
                            <textarea name="nama_petugas" id="nama_petugas" class="form-control border-effect" readonly rows="4">{{ $sppd->nama_petugas }}</textarea>
                        </div>      
                        
                        <div class="form-group mb-3">
                            <label for="lampiran" class="font-semibold">Lampiran</label>
                            <button onclick="window.location.href='{{ asset('storage/' . $sppd->lampiran) }}'" class="btn btn-primary">
                                <i class="fas fa-file-pdf"></i> {{ basename($sppd->lampiran) }}
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="{{ route('sppd-dalam-daerah.index') }}" class="btn-cancel flex items-center justify-center h-12 w-32 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>    
                        <a href="{{ route('sppd-dalam-daerah.edit', $sppd->id) }}" class="btn-edit flex items-center justify-center h-12 w-32 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        
                    </div>
                </div>  
                
            </div>
        </div>
    </div>
@endsection
