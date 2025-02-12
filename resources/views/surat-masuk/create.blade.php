@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah Surat Masuk</h2>
        </div>

        <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-grid">
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
                    <input type="file" name="lampiran" id="lampiran" 
                        class="form-control @error('lampiran') is-invalid @enderror">
                    <div class="form-help">PDF, DOC, atau DOCX (Maksimal 2MB)</div>
                    @error('lampiran')
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