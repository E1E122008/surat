@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Detail Peraturan Daerah</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group mb-3">
                            <label for="no_agenda">Nomor Agenda</label>
                            <input type="text" name="no_agenda" id="no_agenda" class="form-control border-effect" value="{{ $perda->no_agenda }}" readonly>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="no_surat">Nomor Surat</label>
                            <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $perda->no_surat }}" readonly>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="pengirim">Pengirim</label>
                            <input type="text" name="pengirim" id="pengirim" class="form-control border-effect" value="{{ $perda->pengirim }}" readonly>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group mb-3">
                            <label for="tanggal_surat">Tanggal Surat</label>
                            <input type="text" name="tanggal_surat" id="tanggal_surat" class="form-control border-effect" value="{{ $perda->tanggal_surat->format('d/m/Y') }}" readonly>
                        </div>  

                        <div class="form-group mb-3">
                            <label for="tanggal_terima">Tanggal Terima</label>
                            <input type="text" name="tanggal_terima" id="tanggal_terima" class="form-control border-effect" value="{{ $perda->tanggal_terima->format('d/m/Y') }}" readonly>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="perihal">Perihal</label>
                        <textarea name="perihal" id="perihal" class="form-control border-effect" readonly>{{ $perda->perihal }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group mb-3">
                            <label for="disposisi">Disposisi</label>
                            <div class="form-control border-effect bg-gray-50" readonly style="min-height:90px;">
                                @if($perda->disposisi)
                                    @php $disposisiParts = explode('|', $perda->disposisi); @endphp
                                    @foreach($disposisiParts as $part)
                                        {{ trim($part) }}<br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status">Status</label>
                            <div class="form-control border-effect bg-gray-50" readonly style="min-height:90px; display:flex; align-items:center;">
                                @if($perda->status == 'tercatat')
                                    <span class="bg-tercatat">Tercatat</span>
                                @elseif($perda->status == 'terdisposisi')
                                    <span class="bg-terdisposisi">Terdisposisi</span>
                                @elseif($perda->status == 'diproses')
                                    <span class="bg-diproses">Diproses</span>
                                @elseif($perda->status == 'koreksi')
                                    <span class="bg-koreksi">Koreksi</span>
                                @elseif($perda->status == 'diambil')
                                    <span class="bg-diambil">Diambil</span>
                                @elseif($perda->status == 'selesai')
                                    <span class="bg-selesai">Selesai</span>
                                @else
                                    {{ ucfirst($perda->status) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    

                    <div class="form-group mb-3">
                        <label for="lampiran">Lampiran</label>
                        <button onclick="window.location.href='{{ asset('storage/' . $perda->lampiran) }}'" class="btn btn-primary">
                            <i class="fas fa-eye"></i> {{ basename($perda->lampiran) }}
                        </button>
                    </div>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="button-container flex space-x-4">
                        <a href="{{ route('draft-phd.perda.index') }}" class="btn-cancel flex items-center justify-center h-12 w-32 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>

                        <a href="{{ route('draft-phd.perda.edit', $perda->id) }}" class="btn btn-info flex items-center justify-center h-12 w-32 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition duration-200" title="Edit">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
