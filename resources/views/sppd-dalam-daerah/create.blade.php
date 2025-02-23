@extends('layouts.app')

@section('content')
    <div class="form-section">
        <div class="form-header">
            <h2>Tambah SPPD Dalam Daerah</h2>
        </div>

        <form action="{{ route('sppd-dalam-daerah.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6"> 
                 
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
                    <input type="file" name="lampiran" id="lampiran" class="form-control @error('lampiran') is-invalid @enderror">
                    <div class="form-help">PDF, DOC, DOCX, JPG, JPEG, PNG, atau GIF (Maksimal 2MB)</div>
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
@endsection