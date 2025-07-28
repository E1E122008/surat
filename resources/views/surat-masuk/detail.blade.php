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
                        <label for="no_agenda" class="text-sm font-medium">Nomor Agenda</label>
                        <input type="text" name="no_agenda" id="no_agenda" class="form-control border-effect" value="{{ $surat->no_agenda ?? '-' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="no_surat" class="text-sm font-medium">Nomor Surat</label>
                        <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $surat->no_surat ?? '-' }}" readonly>
                    </div>

                    <div class="form-group mb-3">
                        <label for="pengirim" class="text-sm font-medium">Pengirim</label>
                        <input type="text" name="pengirim" id="pengirim" class="form-control border-effect" value="{{ $surat->pengirim ?? '-' }}" readonly>
                    </div> 
                </div> 

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-3">
                        <label for="tanggal_surat" class="text-sm font-medium">Tanggal Surat</label>
                        <input type="text" name="tanggal_surat" id="tanggal_surat" class="form-control border-effect" value="{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}" readonly>
                    </div>
                        
                    <div class=" form-group mb-3">
                        <label for="tanggal_terima" class="text-sm font-medium">Tanggal Terima</label>
                        <input type="text" name="tanggal_terima" id="tanggal_terima" class="form-control border-effect" value="{{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}" readonly>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-group mb-3">
                        <label for="perihal" class="text-sm font-medium">Perihal</label>
                        <textarea name="perihal" id="perihal" class="form-control border-effect" readonly>{{ $surat->perihal ?? '-' }}</textarea>
                    </div>


                    <div class="form-group mb-3">
                        <label for="disposisi" class="text-sm font-medium">Disposisi</label>
                        <textarea type="text" name="disposisi" id="disposisi" class="form-control border-effect" readonly>{{ $surat->disposisi ?? '-' }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="status" class="text-sm font-medium">Status</label>
                        <input type="text" class="form-control border-effect" value="{{ ucfirst($surat->status) }}" readonly>
                    </div>
                    
                    

                    <div class="form-group mb-3">
                        <label for="lampiran" class="text-sm font-medium">Lampiran</label>
                        @php
                            $lampiran = is_array($surat->lampiran) ? $surat->lampiran : json_decode($surat->lampiran, true);
                        @endphp
                        @if($lampiran && count($lampiran))
                            <div class="row">
                                @foreach($lampiran as $file)
                                    @php
                                        if (is_string($file)) {
                                            $file = ['path' => $file, 'name' => basename($file)];
                                        }
                                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                        $iconClass = 'fa-file-alt text-secondary';
                                        if(in_array($ext, ['jpg','jpeg','png','gif'])) $iconClass = 'fa-file-image text-info';
                                        elseif($ext === 'pdf') $iconClass = 'fa-file-pdf text-danger';
                                        elseif(in_array($ext, ['doc','docx'])) $iconClass = 'fa-file-word text-primary';
                                    @endphp
                                    <div class="col-12 mb-2 d-flex align-items-center gap-2">
                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank" class="fs-4 me-2" title="Lihat file">
                                            <i class="fas {{ $iconClass }}"></i>
                                        </a>
                                        <span class="fw-bold small lampiran-filename" title="{{ $file['name'] }}">
                                            {{ $file['name'] }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p>-</p>
                        @endif
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

<style>
.lampiran-filename {
    max-width: 250px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    vertical-align: middle;
}
</style>
