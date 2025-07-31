@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah Surat Masuk</h2>
        </div>

        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="form-group">
                    <label for="no_agenda" class="form-label">Nomor Agenda</label>
                    <input type="text" name="no_agenda" id="no_agenda" 
                        class="form-control @error('no_agenda') is-invalid @enderror"
                        value="{{ old('no_agenda') }}" required>
                    @error('no_agenda')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="no_surat" class="form-label">Nomor Surat</label>
                    <input type="text" name="no_surat" id="no_surat" 
                        class="form-control @error('no_surat') is-invalid @enderror"
                        value="{{ old('no_surat') }}" required>
                    @error('no_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="pengirim" class="form-label">Pengirim</label>
                    <input type="text" name="pengirim" id="pengirim" 
                        class="form-control @error('pengirim') is-invalid @enderror"
                        value="{{ old('pengirim') }}" required>
                    @error('pengirim')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-group">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" 
                        class="form-control @error('tanggal_surat') is-invalid @enderror"
                        value="{{ old('tanggal_surat') }}" required>
                    @error('tanggal_surat')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                    <input type="date" name="tanggal_terima" id="tanggal_terima" 
                        class="form-control @error('tanggal_terima') is-invalid @enderror"
                        value="{{ old('tanggal_terima') }}" required>
                    @error('tanggal_terima')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="perihal" class="form-label">Perihal</label>
                    <textarea name="perihal" id="perihal" rows="3" 
                        class="form-control @error('perihal') is-invalid @enderror"
                        required>{{ old('perihal') }}</textarea>
                    @error('perihal')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="lampiran" class="form-label">Lampiran</label>
                    <input type="file" 
                           name="lampiran[]" 
                           id="lampiran" 
                           multiple
                           class="form-control @error('lampiran') is-invalid @enderror @error('lampiran.*') is-invalid @enderror"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           onchange="previewFiles(this)">
                    <div class="form-help">Pilih satu atau beberapa file (PDF, DOC, DOCX, JPG, JPEG, PNG, maksimal 2MB per file)</div>
                    
                    <!-- File preview -->
                    <div id="filesPreview" class="mt-3 space-y-2 hidden">
                        <h4 class="text-sm font-medium text-gray-600">File yang akan diupload:</h4>
                        <div id="filesList" class="space-y-2"></div>
                    </div>
                    
                    @error('lampiran')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                    @error('lampiran.*')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection

<script>
function previewFiles(input) {
    const preview = document.getElementById('filesPreview');
    const filesList = document.getElementById('filesList');
    
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
</script>