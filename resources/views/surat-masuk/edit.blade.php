@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit Surat Masuk</h2>
                    </div>

                    <form action="{{ route('surat-masuk.update', $suratMasuk->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="no_agenda" class="block text-sm font-medium text-gray-700">Nomor Agenda</label>
                                <input type="text" name="no_agenda" id="no_agenda" 
                                    class="form-control"
                                    value="{{ old('no_agenda', $suratMasuk->no_agenda) }}" required>
                                @error('no_agenda')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" 
                                    class="form-control"
                                    value="{{ old('no_surat', $suratMasuk->no_surat) }}" required>
                                @error('no_surat')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="pengirim" class="block text-sm font-medium text-gray-700">Pengirim</label>
                                <input type="text" name="pengirim" id="pengirim" 
                                    class="form-control"
                                    value="{{ old('pengirim', $suratMasuk->pengirim) }}" required>
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

                            <div class="md:col-span-2">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">Lampiran (PDF, DOC, DOCX, Gambar)</label>
                                
                                <!-- Existing Attachments -->
                                @php
                                    $lampiran = $suratMasuk->lampiran ?? [];
                                @endphp
                                
                                @if(!empty($lampiran) && count($lampiran) > 0)
                                    <div class="mt-3 mb-4">
                                        <h4 class="text-sm font-medium text-gray-600 mb-2">File yang sudah ada:</h4>
                                        <div class="space-y-2">
                                            @foreach($lampiran as $index => $file)
                                                @php
                                                    // Handle both old format (string) and new format (array)
                                                    if (is_string($file)) {
                                                        $filePath = $file;
                                                        $fileName = basename($file);
                                                    } else {
                                                        $filePath = $file['path'] ?? '';
                                                        $fileName = $file['name'] ?? basename($filePath);
                                                    }
                                                    
                                                    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                                                    $iconClass = 'fa-file-alt text-gray-500';
                                                    $bgColor = 'bg-gray-50';
                                                    
                                                    if(in_array($ext, ['jpg','jpeg','png','gif'])) {
                                                        $iconClass = 'fa-file-image text-blue-500';
                                                        $bgColor = 'bg-blue-50';
                                                    } elseif($ext === 'pdf') {
                                                        $iconClass = 'fa-file-pdf text-red-500';
                                                        $bgColor = 'bg-red-50';
                                                    } elseif(in_array($ext, ['doc','docx'])) {
                                                        $iconClass = 'fa-file-word text-indigo-500';
                                                        $bgColor = 'bg-indigo-50';
                                                    }
                                                @endphp
                                                
                                                <div class="flex items-center justify-between p-3 {{ $bgColor }} rounded-lg border" id="attachment-{{ $index }}">
                                                    <div class="flex items-center space-x-3">
                                                        <i class="fas {{ $iconClass }} text-lg"></i>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $fileName }}</p>
                                                            @if(isset($file['size']))
                                                                <p class="text-xs text-gray-500">{{ number_format($file['size'] / 1024, 1) }} KB</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <a href="{{ asset('storage/' . $filePath) }}" 
                                                           target="_blank" 
                                                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded hover:bg-blue-200 transition-colors">
                                                            <i class="fas fa-eye mr-1"></i>
                                                            Lihat
                                                        </a>
                                                        <button type="button" 
                                                                onclick="deleteAttachment({{ $index }}, '{{ $fileName }}')"
                                                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded hover:bg-red-200 transition-colors">
                                                            <i class="fas fa-trash mr-1"></i>
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <!-- New File Upload -->
                                <div class="mt-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ !empty($lampiran) ? 'Tambah file baru:' : 'Upload file:' }}
                                    </label>
                                    <input type="file" 
                                           name="lampiran[]" 
                                           id="lampiran" 
                                           multiple
                                           class="block w-full text-sm text-gray-500
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-blue-50 file:text-blue-700
                                           hover:file:bg-blue-100 border border-gray-300 rounded-md
                                           focus:ring-blue-500 focus:border-blue-500"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           onchange="previewNewFiles(this)">
                                    <p class="mt-1 text-sm text-gray-500">Pilih satu atau beberapa file (PDF, DOC, DOCX, JPG, JPEG, PNG, maksimal 2MB per file)</p>
                                </div>
                                
                                <!-- Preview new files -->
                                <div id="newFilesPreview" class="mt-3 space-y-2 hidden">
                                    <h4 class="text-sm font-medium text-gray-600">File yang akan ditambahkan:</h4>
                                    <div id="newFilesList" class="space-y-2"></div>
                                </div>
                                
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                                @error('lampiran.*')
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
@endsection

<script>
function deleteAttachment(index, fileName) {
    if (confirm(`Apakah Anda yakin ingin menghapus file "${fileName}"?`)) {
        fetch(`{{ route('surat-masuk.delete-attachment', $suratMasuk->id) }}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                attachment_index: index
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the attachment element from DOM
                document.getElementById(`attachment-${index}`).remove();
                
                // Show success message
                showNotification('File berhasil dihapus', 'success');
                
                // Check if no more attachments exist
                const attachmentContainer = document.querySelector('.space-y-2');
                if (attachmentContainer && attachmentContainer.children.length === 0) {
                    const existingSection = document.querySelector('h4:contains("File yang sudah ada:")').parentElement;
                    if (existingSection) {
                        existingSection.style.display = 'none';
                    }
                }
            } else {
                showNotification(data.message || 'Gagal menghapus file', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Terjadi kesalahan saat menghapus file', 'error');
        });
    }
}

function previewNewFiles(input) {
    const preview = document.getElementById('newFilesPreview');
    const filesList = document.getElementById('newFilesList');
    
    filesList.innerHTML = '';
    
    if (input.files.length > 0) {
        preview.classList.remove('hidden');
        
        Array.from(input.files).forEach((file, index) => {
            const fileSize = (file.size / 1024).toFixed(1);
            const ext = file.name.split('.').pop().toLowerCase();
            
            let iconClass = 'fa-file-alt text-gray-500';
            let bgColor = 'bg-gray-50';
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
                iconClass = 'fa-file-image text-blue-500';
                bgColor = 'bg-blue-50';
            } else if (ext === 'pdf') {
                iconClass = 'fa-file-pdf text-red-500';
                bgColor = 'bg-red-50';
            } else if (['doc', 'docx'].includes(ext)) {
                iconClass = 'fa-file-word text-indigo-500';
                bgColor = 'bg-indigo-50';
            }
            
            const fileElement = document.createElement('div');
            fileElement.className = `flex items-center space-x-3 p-3 ${bgColor} rounded-lg border`;
            fileElement.innerHTML = `
                <i class="fas ${iconClass} text-lg"></i>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} KB</p>
                </div>
            `;
            
            filesList.appendChild(fileElement);
        });
    } else {
        preview.classList.add('hidden');
    }
}

function showNotification(message, type = 'success') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-md text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Add CSRF token to page head if not already present
if (!document.querySelector('meta[name="csrf-token"]')) {
    const csrfMeta = document.createElement('meta');
    csrfMeta.name = 'csrf-token';
    csrfMeta.content = '{{ csrf_token() }}';
    document.head.appendChild(csrfMeta);
}
</script>