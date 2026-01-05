@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit Surat Masuk</h2>
                    </div>

                    <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data" id="editForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="no_agenda" class="block text-sm font-medium text-gray-700">Nomor Agenda</label>
                                <input type="text" name="no_agenda" id="no_agenda" 
                                    class="form-control"
                                    value="{{ old('no_agenda', $suratMasuk->no_agenda) }}" 
                                    placeholder="Masukkan nomor agenda surat masuk"
                                    autofocus>
                                @error('no_agenda')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">Nomor Surat <span class="text-red-500">*</span></label>
                                <input type="text" name="no_surat" id="no_surat" 
                                    class="form-control"
                                    value="{{ old('no_surat', $suratMasuk->no_surat) }}" 
                                    placeholder="Masukkan nomor surat dari pengirim"
                                    required>
                                @error('no_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pengirim" class="block text-sm font-medium text-gray-700">Pengirim <span class="text-red-500">*</span></label>
                                <input type="text" name="pengirim" id="pengirim" 
                                    class="form-control"
                                    value="{{ old('pengirim', $suratMasuk->pengirim) }}" 
                                    placeholder="Nama instansi atau pengirim surat"
                                    required>
                                @error('pengirim')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" id="tanggal_surat" 
                                    class="form-control"
                                    value="{{ old('tanggal_surat', $suratMasuk->tanggal_surat ? $suratMasuk->tanggal_surat->format('Y-m-d') : '') }}" required>
                                @error('tanggal_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_terima" class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima" 
                                    class="form-control"
                                    value="{{ old('tanggal_terima', $suratMasuk->tanggal_terima ? $suratMasuk->tanggal_terima->format('Y-m-d') : '') }}" required>
                                @error('tanggal_terima')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="perihal" class="block text-sm font-medium text-gray-700 mb-2">Perihal</label>
                                <textarea 
                                    name="perihal" 
                                    id="perihal" 
                                    class="form-textarea" 
                                    placeholder="Masukkan perihal surat" 
                                    required
                                >{{ old('perihal', $suratMasuk->perihal) }}</textarea>
                                @error('perihal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="lampiran" class="text-sm font-medium">Lampiran Saat Ini</label>
                                @php
                                    $lampiran = is_array($suratMasuk->lampiran) ? $suratMasuk->lampiran : json_decode($suratMasuk->lampiran, true);
                                @endphp
                                @if($lampiran && count($lampiran))
                                    <div class="mt-2 space-y-3">
                                        @foreach($lampiranLama as $idx => $file)
                                            @php
                                                $ext = isset($file['name']) ? strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) : '';
                                                $iconClass = 'fa-file-alt text-gray-500';
                                                if (in_array($ext, ['pdf'])) $iconClass = 'fa-file-pdf text-red-500';
                                                elseif (in_array($ext, ['doc', 'docx'])) $iconClass = 'fa-file-word text-blue-600';
                                                elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'])) $iconClass = 'fa-file-image text-blue-500';
                                            @endphp
                                            <div class="lampiran-item flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 hover:bg-gray-100 transition-colors duration-200" data-index="{{ $idx }}" data-path="{{ $file['path'] }}">
                                                <div class="flex items-center flex-1 min-w-0">
                                                    <i class="fas {{ $iconClass }} text-xl mr-3 flex-shrink-0"></i>
                                                    <div class="flex-1 min-w-0">
                                                        <a href="{{ asset('storage/' . $file['path']) }}" target="_blank"
                                                           class="text-gray-900 font-medium hover:text-blue-600 transition-colors duration-200 truncate block"
                                                           title="{{ $file['name'] }}">
                                                            {{ $file['name'] }}
                                                        </a>
                                                        <p class="text-xs text-gray-500 mt-1">
                                                            {{ strtoupper($ext) }} • {{ number_format(filesize(public_path('storage/' . $file['path'])) / 1024, 1) }} KB
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center space-x-2 ml-4">
                                                    
                                                    <button type="button"
                                                        onclick="hapusLampiran({{ $idx }}, '{{ $file['name'] }}')"
                                                        class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors duration-200"
                                                        title="Hapus lampiran">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <input type="hidden" name="lampiran_lama[]" value="{{ $file['path'] }}" class="lampiran-input">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="mt-2 text-gray-500 italic">Tidak ada lampiran</div>
                                @endif
                            </div>
                            
                            <div class="form-group md:col-span-2">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">Tambah Lampiran Baru</label>
                                
                                <!-- Drag & Drop Zone -->
                                <div id="drag-drop-zone" class="drag-drop-zone">
                                    <div class="drag-drop-content">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Drag & Drop file di sini</h3>
                                        <p class="text-sm text-gray-500 mb-4">atau</p>
                                        <button type="button" id="browse-btn" class="btn btn-primary">
                                            <i class="fas fa-folder-open mr-2"></i>
                                            Pilih File
                                        </button>
                                        <p class="text-xs text-gray-400 mt-3">
                                            Format yang didukung: PDF, DOC, DOCX, JPG, JPEG, PNG<br>
                                            Maksimal ukuran: 2GB per file
                                        </p>
                                    </div>
                                </div>
                                
                                <!-- Hidden file input -->
                                <input type="file" name="lampiran[]" id="lampiran" class="hidden" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                
                                <!-- File preview container -->
                                <div id="file-preview" class="mt-4 space-y-2"></div>
                                
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="button-container">
                            <a href="{{ route('surat-masuk.detail', $suratMasuk->id) }}" 
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
        </div>
    </div>
    
    <style>
        .drag-drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #f9fafb;
            cursor: pointer;
        }
        
        .drag-drop-zone.dragover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            transform: scale(1.02);
        }
        
        .drag-drop-zone:hover {
            border-color: #9ca3af;
            background-color: #f3f4f6;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Array untuk melacak lampiran yang dihapus
        let lampiranDihapus = [];
        let selectedFiles = [];
        
        document.addEventListener('DOMContentLoaded', function() {
            const dragZone = document.getElementById('drag-drop-zone');
            const fileInput = document.getElementById('lampiran');
            const browseBtn = document.getElementById('browse-btn');
            const filePreview = document.getElementById('file-preview');
            
            // Browse button click
            browseBtn.addEventListener('click', function() {
                fileInput.click();
            });
            
            // File input change
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });
            
            // Drag and drop events
            dragZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                dragZone.classList.add('dragover');
            });
            
            dragZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dragZone.classList.remove('dragover');
            });
            
            dragZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dragZone.classList.remove('dragover');
                handleFiles(e.dataTransfer.files);
            });
            
            // Click on drag zone
            dragZone.addEventListener('click', function() {
                fileInput.click();
            });
            
            function handleFiles(files) {
                for (let file of files) {
                    // Validate file type
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
                    
                    if (!allowedTypes.includes(file.type)) {
                        showError(`File "${file.name}" tidak didukung. Hanya PDF, DOC, DOCX, JPG, JPEG, PNG yang diizinkan.`);
                        continue;
                    }
                    
                    // Validate file size (2GB = 2 * 1024 * 1024 * 1024 bytes)
                    if (file.size > 2 * 1024 * 1024 * 1024) {
                        showError(`File "${file.name}" terlalu besar. Maksimal 2GB per file.`);
                        continue;
                    }
                    
                    // Add file to selected files
                    selectedFiles.push(file);
                    addFilePreview(file);
                }
                
                // Update file input
                updateFileInput();
            }
            
            function addFilePreview(file) {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item flex items-center justify-between bg-blue-50 rounded-lg px-4 py-3 border border-blue-200';
                fileItem.dataset.name = file.name;
                
                const fileIcon = getFileIcon(file.type);
                const fileSize = formatFileSize(file.size);
                const fileExt = file.name.split('.').pop().toUpperCase();
                
                fileItem.innerHTML = `
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="fas ${fileIcon} text-xl mr-3 flex-shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <div class="text-gray-900 font-medium truncate block" title="${file.name}">
                                ${file.name}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                ${fileExt} • ${fileSize}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <button type="button" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors duration-200" onclick="removeNewFile('${file.name}')" title="Hapus file">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                filePreview.appendChild(fileItem);
            }
            
            function removeNewFile(fileName) {
                // Remove from selected files
                selectedFiles = selectedFiles.filter(file => file.name !== fileName);
                
                // Remove from preview
                const fileItem = filePreview.querySelector(`[data-name="${fileName}"]`);
                if (fileItem) {
                    fileItem.remove();
                }
                
                // Update file input
                updateFileInput();
            }
            
            function updateFileInput() {
                // Create new FileList-like object
                const dt = new DataTransfer();
                selectedFiles.forEach(file => dt.items.add(file));
                fileInput.files = dt.files;
            }
            
            function getFileIcon(fileType) {
                switch(fileType) {
                    case 'application/pdf':
                        return 'fas fa-file-pdf text-red-500';
                    case 'application/msword':
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        return 'fas fa-file-word text-blue-500';
                    case 'image/jpeg':
                    case 'image/jpg':
                    case 'image/png':
                        return 'fas fa-file-image text-green-500';
                    default:
                        return 'fas fa-file text-gray-500';
                }
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
            
            function showError(message) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
            
            // Make removeNewFile function global
            window.removeNewFile = removeNewFile;
        });
        
        function hapusLampiran(index, filename) {
            Swal.fire({
                title: 'Hapus Lampiran?',
                text: `Lampiran "${filename}" akan dihapus dari daftar!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF4757',
                cancelButtonColor: '#747D8C',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari elemen lampiran berdasarkan index
                    const lampiranItems = document.querySelectorAll('.lampiran-item');
                    let targetItem = null;
                    
                    for (let item of lampiranItems) {
                        if (parseInt(item.dataset.index) === index) {
                            targetItem = item;
                            break;
                        }
                    }
                    
                    if (targetItem) {
                        // Tambahkan ke array lampiran yang dihapus
                        const path = targetItem.dataset.path;
                        if (path) {
                            lampiranDihapus.push(path);
                        }
                        
                        // Hapus elemen dari DOM
                        targetItem.remove();
                        
                        // Update input hidden untuk lampiran yang dihapus
                        updateLampiranInputs();
                        
                        Swal.fire(
                            'Terhapus!',
                            'Lampiran berhasil dihapus dari daftar.',
                            'success'
                        );
                    }
                }
            });
        }
        
        function updateLampiranInputs() {
            // Hapus semua input hidden lampiran_lama yang ada
            const existingInputs = document.querySelectorAll('input[name="lampiran_lama[]"]');
            existingInputs.forEach(input => input.remove());
            
            // Tambahkan kembali input hidden untuk lampiran yang tidak dihapus
            const lampiranItems = document.querySelectorAll('.lampiran-item');
            lampiranItems.forEach(item => {
                const path = item.dataset.path;
                if (path && !lampiranDihapus.includes(path)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'lampiran_lama[]';
                    input.value = path;
                    item.appendChild(input);
                }
            });
        }
        
        // Tambahkan input hidden untuk lampiran yang dihapus
        document.getElementById('editForm').addEventListener('submit', function(e) {
            // Tambahkan input hidden untuk setiap lampiran yang dihapus
            lampiranDihapus.forEach(path => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'lampiran_dihapus[]';
                input.value = path;
                this.appendChild(input);
            });
            
            // Tampilkan loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            }
        });
    </script>
@endsection