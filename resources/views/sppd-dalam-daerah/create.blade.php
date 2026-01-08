@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah SPPD Dalam Daerah</h2>
        </div>

        <form action="{{ route('sppd-dalam-daerah.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> 
                 
                <div class="form-group">
                    <label for="no_surat" class="form-label">Nomor Surat</label>
                    <input type="text" name="no_surat" id="no_surat" 
                        class="form-control @error('no_surat') is-invalid @enderror"
                        value="{{ $nomorSurat }}" >
                    @error('no_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" 
                        class="form-control @error('tanggal') is-invalid @enderror"
                        value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tujuan" class="form-label">Tujuan</label>
                    <input name="tujuan" id="tujuan" rows="4" 
                        class="form-control @error('tujuan') is-invalid @enderror"
                        style="height: 50px;" required>{{ old('tujuan') }}</input>
                    @error('tujuan')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div> 
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                <div class="form-group">
                    <label for="perihal" class="form-label">Perihal</label>
                    <textarea name="perihal" id="perihal" rows="2" 
                        class="form-control @error('perihal') is-invalid @enderror"
                        required>{{ old('perihal') }}</textarea>
                    @error('perihal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_petugas" class="form-label">Nama Petugas</label>
                    <textarea name="nama_petugas" id="nama_petugas" rows="2" 
                        class="form-control @error('nama_petugas') is-invalid @enderror"
                        required>{{ old('nama_petugas') }}</textarea>
                    
                    @error('nama_petugas')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="lampiran" class="form-label">Lampiran</label>
                    
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
                    <input type="file" name="lampiran[]" id="lampiran" class="hidden" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                    
                    <!-- File preview container -->
                    <div id="file-preview" class="mt-4 space-y-2"></div>
                    
                    @error('lampiran')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('sppd-dalam-daerah.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
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
        
        .file-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background-color: #f9fafb;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }
        
        .file-item .file-info {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        .file-item .file-icon {
            margin-right: 0.75rem;
            font-size: 1.25rem;
        }
        
        .file-item .file-details {
            flex: 1;
        }
        
        .file-item .file-name {
            font-weight: 500;
            color: #374151;
        }
        
        .file-item .file-size {
            font-size: 0.875rem;
            color: #6b7280;
        }
        
        .file-item .remove-btn {
            padding: 0.25rem;
            color: #ef4444;
            border-radius: 4px;
            transition: all 0.2s ease;
            background: none;
            border: none;
            cursor: pointer;
        }
        
        .file-item .remove-btn:hover {
            background-color: #fef2f2;
        }
    </style>

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dragZone = document.getElementById('drag-drop-zone');
            const fileInput = document.getElementById('lampiran');
            const browseBtn = document.getElementById('browse-btn');
            const filePreview = document.getElementById('file-preview');
            
            let selectedFiles = [];
            
            browseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fileInput.click();
            });
            
            fileInput.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });
            
            dragZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragZone.classList.add('dragover');
            });
            
            dragZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragZone.classList.remove('dragover');
            });
            
            dragZone.addEventListener('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dragZone.classList.remove('dragover');
                handleFiles(e.dataTransfer.files);
            });
            
            dragZone.addEventListener('click', function(e) {
                if (e.target.closest('#browse-btn')) {
                    return;
                }
                fileInput.click();
            });
            
            function handleFiles(files) {
                for (let file of files) {
                    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'image/jpeg', 'image/jpg', 'image/png'];
                    
                    if (!allowedTypes.includes(file.type)) {
                        showError(`File "${file.name}" tidak didukung. Hanya PDF, DOC, DOCX, JPG, JPEG, PNG yang diizinkan.`);
                        continue;
                    }
                    
                    if (file.size > 2 * 1024 * 1024 * 1024) {
                        showError(`File "${file.name}" terlalu besar. Maksimal 2GB per file.`);
                        continue;
                    }
                    
                    selectedFiles.push(file);
                    addFilePreview(file);
                }
                
                updateFileInput();
            }
            
            function addFilePreview(file) {
                const fileItem = document.createElement('div');
                fileItem.className = 'file-item';
                fileItem.dataset.name = file.name;
                
                const fileIcon = getFileIcon(file.type);
                const fileSize = formatFileSize(file.size);
                const fileExt = file.name.split('.').pop().toUpperCase();
                
                fileItem.innerHTML = `
                    <div class="file-info">
                        <i class="file-icon ${fileIcon}"></i>
                        <div class="file-details">
                            <div class="file-name">${file.name}</div>
                            <div class="file-size">${fileExt} • ${fileSize}</div>
                        </div>
                    </div>
                    <button type="button" class="remove-btn" onclick="removeFile('${file.name}')">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                
                filePreview.appendChild(fileItem);
            }
            
            function removeFile(fileName) {
                selectedFiles = selectedFiles.filter(file => file.name !== fileName);
                const fileItem = filePreview.querySelector(`[data-name="${fileName}"]`);
                if (fileItem) {
                    fileItem.remove();
                }
                updateFileInput();
            }
            
            function updateFileInput() {
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
                    title: 'Error',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
            
            window.removeFile = removeFile;
        });
    </script>
@endsection