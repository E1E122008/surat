@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Detail SPT Dalam Daerah</h2>
                </div>

                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="no_surat" class="font-semibold">No Surat</label>
                            <input type="text" name="no_surat" id="no_surat" class="form-control border-effect" value="{{ $spt->no_surat }}" readonly>
                        </div>      

                        <div class="form-group">
                            <label for="tanggal" class="font-semibold">Tanggal</label>
                            <input type="text" name="tanggal" id="tanggal" class="form-control border-effect" value="{{ $spt->tanggal->format('d/m/Y') }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="tujuan" class="font-semibold">Tujuan</label>      
                            <input type="text" name="tujuan" id="tujuan" class="form-control border-effect" value="{{ $spt->tujuan }}" readonly>
                        </div>
                    </div>  

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="perihal" class="font-semibold">Perihal</label>
                            <textarea name="perihal" id="perihal" class="form-control border-effect" readonly rows="4">{{ $spt->perihal }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="nama_petugas" class="font-semibold" >Nama Petugas</label>
                            <textarea name="nama_petugas" id="nama_petugas" class="form-textarea" readonly rows="4">{{ $spt->nama_petugas }}</textarea>
                        </div>

                        <div class="form-group mb-3 md:col-span-2">
                            <label for="lampiran" class="text-sm font-medium">Lampiran</label>
                            @php
                                $lampiran = is_array($spt->lampiran) ? $spt->lampiran : json_decode($spt->lampiran, true);
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
                                        @endphp
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                            <div class="flex items-center flex-1 min-w-0">
                                                <i class="fas {{ $iconClass }} text-xl mr-3 flex-shrink-0"></i>
                                                <div class="flex-1 min-w-0">
                                                    <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
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
                                                <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                   class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors duration-200"
                                                   title="Lihat file">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ asset('storage/' . $file['path']) }}" download
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
                    <div class="form-actions">
                        <a href="{{ route('spt-luar-daerah.index') }}" class="btn-cancel flex items-center justify-center h-12 w-32 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <a href="{{ route('spt-luar-daerah.edit', $spt->id) }}" class="btn-edit flex items-center justify-center h-12 w-32 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


