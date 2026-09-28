@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <a href="{{ route('draft-phd.perda.index') }}">Draft Perda</a>
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;">Detail</span>
@endsection


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
                            <label for="disposisi" class="text-sm font-medium d-block mb-1">Disposisi</label>
                            <div class="p-3 border rounded shadow-sm" style="background-color: #f8fafc; min-height: 80px;">
                                @php
                                    $disposisiParts = !empty($perda->disposisi) ? array_filter(explode('|', $perda->disposisi), 'trim') : [];
                                    $persetujuanKetua = null;
                                    $tujuanDisposisi = null;
                                    $subDisposisi = null;
                                    $tanggalDisposisi = null;
                                    $catatan = null;
                                    $otherParts = [];

                                    foreach ($disposisiParts as $index => $part) {
                                        $trimmedPart = trim($part);
                                        if (preg_match('/(Sudah|Belum)\s+di\s+Setujui\s+(Kepala|Ketua)\s+Biro\s+Hukum/i', $trimmedPart)) {
                                            $persetujuanKetua = $trimmedPart;
                                        } elseif (stripos($trimmedPart, 'Persetujuan Ke') !== false && stripos($trimmedPart, 'Biro Hukum:') !== false) {
                                            $persetujuanKetua = $trimmedPart;
                                        } elseif (strpos($trimmedPart, 'Diteruskan ke:') !== false) {
                                            $subDisposisi = trim(str_replace('Diteruskan ke:', '', $trimmedPart));
                                        } elseif (strpos($trimmedPart, 'Tanggal:') !== false) {
                                            $tanggalDisposisi = trim(str_replace('Tanggal:', '', $trimmedPart));
                                        } elseif (strpos($trimmedPart, 'Catatan:') !== false) {
                                            $catatan = trim(str_replace('Catatan:', '', $trimmedPart));
                                        } elseif ($index === 0 && !$persetujuanKetua) {
                                            $tujuanDisposisi = $trimmedPart;
                                        } else {
                                            $otherParts[] = $trimmedPart;
                                        }
                                    }
                                    if (!$tujuanDisposisi && count($otherParts) > 0) {
                                        $tujuanDisposisi = $otherParts[0];
                                        $otherParts = array_slice($otherParts, 1);
                                    }
                                @endphp

                                @if(empty($disposisiParts))
                                    <span class="text-muted">- Belum ada disposisi -</span>
                                @else
                                    <div class="d-flex flex-column align-items-start text-start" style="gap: 8px;">
                                        @if ($persetujuanKetua)
                                            <span class="badge {{ stripos($persetujuanKetua, 'Sudah') !== false ? 'bg-success' : 'bg-warning text-dark' }} shadow-sm" style="font-size: 0.75rem; padding: 6px 12px; border-radius: 6px; font-weight: 500; letter-spacing: 0.3px;">
                                                <i class="fas {{ stripos($persetujuanKetua, 'Sudah') !== false ? 'fa-check' : 'fa-clock' }} me-1"></i> {{ $persetujuanKetua }}
                                            </span>
                                        @endif
                                        @if ($tujuanDisposisi)
                                            <div style="font-size: 0.85rem; font-weight: 600; color: #1e293b; display: flex; align-items: flex-start; gap: 8px; padding-top: 4px;">
                                                <i class="fas fa-level-down-alt text-primary mt-1" style="transform: rotate(90deg); font-size: 0.8rem; margin-left: 2px;"></i> 
                                                <span style="flex: 1; line-height: 1.4;">{{ $tujuanDisposisi }}</span>
                                            </div>
                                        @endif
                                        @if ($subDisposisi)
                                            <div style="font-size: 0.8rem; color: #475569; display: flex; align-items: flex-start; gap: 8px;">
                                                <i class="fas fa-angle-double-right text-muted mt-1" style="font-size: 0.75rem; margin-left: 1px;"></i>
                                                <span style="flex: 1; line-height: 1.4;">
                                                    <span class="fw-bold" style="color: #334155;">Diteruskan:</span> {{ $subDisposisi }}
                                                </span>
                                            </div>
                                        @endif
                                        @if ($catatan)
                                            <div class="w-100 mt-1" style="background-color: #ffffff; border-left: 3px solid #3b82f6; padding: 8px 12px; border-radius: 4px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                                <div style="font-size: 0.65rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px;">
                                                    <i class="fas fa-comment-alt me-1"></i> Catatan
                                                </div>
                                                <div class="fst-italic" style="font-size: 0.8rem; color: #334155; line-height: 1.4;">
                                                    {{ $catatan }}
                                                </div>
                                            </div>
                                        @endif
                                        @if ($tanggalDisposisi)
                                            <div class="mt-1" style="font-size: 0.75rem; color: #64748b; font-weight: 500; display: flex; align-items: center; gap: 6px;">
                                                <i class="far fa-calendar-alt" style="color: #94a3b8;"></i> 
                                                <span>{{ $tanggalDisposisi }}</span>
                                            </div>
                                        @endif
                                        @if (count($otherParts) > 0)
                                            <div class="mt-1" style="font-size: 0.75rem; color: #64748b;">
                                                @foreach ($otherParts as $part)
                                                    <div class="mb-1" style="display: flex; align-items: flex-start; gap: 6px;">
                                                        <i class="fas fa-circle mt-1" style="font-size: 4px; color: #cbd5e1;"></i>
                                                        <span>{{ $part }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
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
                                                    {{ strtoupper($ext) }} â€¢ {{ $fileSize }}
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

                        @if(auth()->user()->role !== 'monitor')
                            <a href="{{ route('draft-phd.perda.edit', $perda->id) }}" class="btn btn-info flex items-center justify-center h-12 w-32 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition duration-200" title="Edit">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection



