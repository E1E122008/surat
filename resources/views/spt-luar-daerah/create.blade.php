@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah SPT Luar Daerah</h2>
        </div>

        <form action="{{ route('spt-luar-daerah.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
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
                        value="{{ $nomorSurat }}" readonly>
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
                    <label for="nama_petugas" class="form-label">Nama Petugas</label>
                    <textarea name="nama_petugas" id="nama_petugas" rows="4" 
                        class="form-control @error('nama_petugas') is-invalid @enderror"
                        required>{{ old('nama_petugas') }}</textarea>
                    <div class="form-help">Masukkan nama-nama petugas yang ditugaskan</div>
                    @error('nama_petugas')
                        <div class="form-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group form-grid-full">
                    <label for="lampiran" class="form-label">Lampiran</label>
                    <input type="file" name="lampiran" id="lampiran" 
                        class="form-control @error('lampiran') is-invalid @enderror">
                    <div class="form-help">PDF, DOC, atau DOCX (Maksimal 2MB)</div>
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('spt-luar-daerah.index') }}" class="btn btn-cancel">Batal</a>
                <button type="submit" class="btn btn-submit">Simpan</button>
            </div>
        </form>
    </div>
@endsection