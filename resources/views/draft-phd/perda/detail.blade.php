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
                            <label for="disposisi" class="text-sm font-medium">Disposisi</label>
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
                            <label for="status" class="text-sm font-medium">Status</label>
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

                    

                    <div class="form-group md:col-span-2">
                        <label for="lampiran" class="text-sm font-medium">Lampiran</label>
                        @php
                            $lampiran = is_array($perda->lampiran) ? $perda->lampiran : json_decode($perda->lampiran, true);
                        @endphp
                        @if($lampiran && count($lampiran))
                            <div class="mt-2 space-y-3">
                                @foreach($lampiran as $file)
                                    @php
                                        if (is_string($file)) {
                                            $file = ['path' => $file, 'name' => basename($file)];
                                        }
                                        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                        $iconClass = 'fa-file-alt text-gray-500';
                                        if(in_array($ext, ['jpg','jpeg','png','gif'])) $iconClass = 'fa-file-image text-blue-500';
                                        elseif($ext === 'pdf') $iconClass = 'fa-file-pdf text-red-500';
                                        elseif(in_array($ext, ['doc','docx'])) $iconClass = 'fa-file-word text-blue-600';
                                        // Encode path dengan benar untuk menghindari masalah dengan karakter khusus
                                        $pathParts = explode('/', $file['path']);
                                        $encodedParts = array_map('rawurlencode', $pathParts);
                                        $fileUrl = asset('storage/' . implode('/', $encodedParts));
                                    @endphp
                                    <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="flex items-center flex-1 min-w-0">
                                            <i class="fas {{ $iconClass }} text-xl mr-3 flex-shrink-0"></i>
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ $fileUrl }}" target="_blank"
                                                   class="text-gray-900 font-medium hover:text-blue-600 transition-colors duration-200 truncate block"
                                                   title="{{ $file['name'] }}">
                                                    {{ $file['name'] }}
                                                </a>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    @php
                                                        $filePath = public_path('storage/' . $file['path']);
                                                        $fileSize = file_exists($filePath) ? number_format(filesize($filePath) / 1024, 1) . ' KB' : 'File tidak ditemukan';
                                                    @endphp
                                                    {{ strtoupper($ext) }} • {{ $fileSize }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2 ml-4">
                                            <a href="{{ $fileUrl }}" target="_blank"
                                               class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors duration-200"
                                               title="Lihat file">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ $fileUrl }}" download
                                               class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-full transition-colors duration-200"
                                               title="Download file">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mt-2 text-gray-500 italic">Tidak ada lampiran</div>
                        @endif
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
