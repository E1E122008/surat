@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <a href="{{ route('sk-karo.index') }}">SK Kepala Biro</a>
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;">Tambah Baru</span>
@endsection


@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah SK KARO Baru</h2>
        </div>

        <form action="{{ route('sk-karo.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="no_sk" class="form-label">No SK</label>
                    <input type="text" name="no_sk" id="no_sk"
                        class="form-control @error('no_sk') is-invalid @enderror" value="{{ old('no_sk', $nomor_sk) }}"
                        required placeholder="Masukkan nomor surat...">
                    @error('no_sk')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_sk" class="form-label">Tanggal SK</label>
                    <input type="date" name="tanggal_sk" id="tanggal_sk"
                        class="form-control @error('tanggal_sk') is-invalid @enderror"
                        value="{{ old('tanggal_sk', date('Y-m-d')) }}" required>
                    @error('tanggal_sk')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group form-grid-full">
                    <label for="perihal" class="form-label">Tentang / Perihal</label>
                    <textarea name="perihal" id="perihal" rows="3" class="form-control @error('perihal') is-invalid @enderror"
                        required placeholder="Masukkan perihal...">{{ old('perihal') }}</textarea>
                    @error('perihal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="pejabat_ttd" class="form-label">Pejabat TTD</label>
                    <input type="text" name="pejabat_ttd" id="pejabat_ttd"
                        class="form-control @error('pejabat_ttd') is-invalid @enderror" value="{{ old('pejabat_ttd') }}"
                        required placeholder="Nama / Jabatan...">
                    @error('pejabat_ttd')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="file_surat" class="form-label">Lampiran</label>

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
                                Maksimal ukuran: 5MB per file
                            </p>
                        </div>
                    </div>

                    <!-- Hidden file input -->
                    <input type="file" name="file_surat[]" id="file_surat" class="hidden" multiple
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">

                    <!-- File preview container -->
                    <div id="file-preview" class="mt-4 space-y-2"></div>

                    @error('file_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('sk-karo.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
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
            const fileInput = document.getElementById('file-surat');
            const browseBtn = document.getElementById('browse-btn');
            const filePreview = document.getElementById('file-preview');

            // Perbaiki selector ID file input jika berbeda dengan ID asli (fallback ke pencarian via selector tag)
            const inputElement = document.getElementById('file_surat');

            let selectedFiles = [];

            // Browse button click
            browseBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                inputElement.click();
            });

            // File input change
            inputElement.addEventListener('change', function(e) {
                handleFiles(e.target.files);
            });

            // Drag and drop events
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

            // Click on drag zone
            dragZone.addEventListener('click', function(e) {
                // Don't trigger if clicking on button
                if (e.target.closest('#browse-btn')) {
                    return;
                }
                inputElement.click();
            });

            function handleFiles(files) {
                for (let file of files) {
                    // Validate file type
                    const allowedTypes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                        'image/jpg',
                        'image/png'
                    ];

                    if (!allowedTypes.includes(file.type)) {
                        showError(
                            `File "${file.name}" tidak didukung. Hanya PDF, DOC, DOCX, JPG, JPEG, PNG yang diizinkan.`
                        );
                        continue;
                    }

                    // Validate file size (5MB = 5 * 1024 * 1024 bytes)
                    if (file.size > 5 * 1024 * 1024) {
                        showError(`File "${file.name}" terlalu besar. Maksimal 5MB per file.`);
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
                inputElement.files = dt.files;
            }

            function getFileIcon(fileType) {
                switch (fileType) {
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

            // Make removeFile function global
            window.removeFile = removeFile;
        });
    </script>
@endsection
