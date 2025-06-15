@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah SK</h2>
        </div>

        <form action="{{ route('draft-phd.sk.store') }}" method="POST" enctype="multipart/form-data">
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
                    <label for="catatan" class="form-label">Catatan User</label>
                    <textarea name="catatan" id="catatan" rows="3" 
                        class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="disposisi" class="form-label">Disposisi</label>
                    <input type="text" name="disposisi" id="disposisi" 
                        class="form-control @error('disposisi') is-invalid @enderror"
                        value="{{ old('disposisi') }}">
                    @error('disposisi')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="admin_notes" class="form-label">Catatan Admin</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                        class="form-control @error('admin_notes') is-invalid @enderror">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="lampiran" class="form-label">Lampiran</label>
                    <input type="file" name="lampiran" id="lampiran" 
                        class="form-control @error('lampiran') is-invalid @enderror">
                    <div class="form-help">PDF, DOC, DOCX, atau Gambar (Maksimal 2MB)</div>
                    @error('lampiran')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('draft-phd.sk.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection