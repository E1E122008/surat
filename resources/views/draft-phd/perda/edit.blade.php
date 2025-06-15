@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h2 class="text-2xl font-semibold">Edit Draft Perda</h2>
                    </div>

                    <form action="{{ route('draft-phd.perda.update', $perda->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')  

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="form-group">
                                <label for="no_agenda" class="form-label">Nomor Agenda</label>
                                <input type="text" name="no_agenda" id="no_agenda" 
                                    class="form-control @error('no_agenda') is-invalid @enderror"   
                                    value="{{ old('no_agenda', $perda->no_agenda) }}" required>
                                @error('no_agenda')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="no_surat" class="form-label">Nomor Surat</label>
                                <input type="text" name="no_surat" id="no_surat" 
                                    class="form-control @error('no_surat') is-invalid @enderror"
                                    value="{{ old('no_surat', $perda->no_surat) }}" required>   
                                @error('no_surat')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>  
                            
                            <div class="form-group">
                                <label for="pengirim" class="form-label">Pengirim</label>
                                <input type="text" name="pengirim" id="pengirim" 
                                    class="form-control @error('pengirim') is-invalid @enderror"
                                    value="{{ old('pengirim', $perda->pengirim) }}" required>
                                @error('pengirim')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div> 
                        </div>

                        <div class="mt-6"></div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-group">
                                <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" id="tanggal_surat" 
                                    class="form-control @error('tanggal_surat') is-invalid @enderror"
                                    value="{{ old('tanggal_surat', $perda->tanggal_surat ?  $perda->tanggal_surat->format('Y-m-d') : '') }}" required>
                                @error('tanggal_surat')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="tanggal_terima" class="form-label">Tanggal Terima</label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima"        
                                    class="form-control @error('tanggal_terima') is-invalid @enderror"
                                    value="{{ old('tanggal_terima', $perda->tanggal_terima ? $perda->tanggal_terima->format('Y-m-d') : '') }}" required>
                                @error('tanggal_terima')
                                    <div class="form-error">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="perihal" class="block text-sm font-medium text-gray-700 mb-2">Perihal</label>
                                <textarea name="perihal" id="perihal" rows="3"                  
                                    class="form-textarea"    
                                    placeholder="Masukkan perihal surat" 
                                    required
                                >{{ old('perihal', $perda->perihal) }}</textarea>
                                @error('perihal')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>  

                            <div class="form-group md:col-span-2">
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">Catatan User</label>
                                <textarea name="catatan" id="catatan" rows="3" 
                                    class="form-textarea @error('catatan') is-invalid @enderror">{{ old('catatan', $perda->catatan) }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="disposisi" class="block text-sm font-medium text-gray-700 mb-2">Disposisi</label>
                                <input type="text" name="disposisi" id="disposisi" 
                                    class="form-control @error('disposisi') is-invalid @enderror"
                                    value="{{ old('disposisi', $perda->disposisi) }}">
                                @error('disposisi')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group md:col-span-2">
                                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                                <textarea name="admin_notes" id="admin_notes" rows="3" 
                                    class="form-textarea @error('admin_notes') is-invalid @enderror">{{ old('admin_notes', $perda->admin_notes) }}</textarea>
                                @error('admin_notes')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">Lampiran (PDF, DOC, DOCX, Gambar)</label>
                                @if($perda->lampiran)
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">File saat ini: {{ basename($perda->lampiran) }}</p>
                                    </div>
                                @endif
                                <input type="file" name="lampiran" id="lampiran" 
                                    class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <p class="mt-1 text-sm text-gray-500">Biarkan kosong jika tidak ingin mengubah file</p>
                                @error('lampiran')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>  
                        </div>

                        <div class="button-container"></div>
                            <a href="{{ route('draft-phd.perda.detail', $perda->id) }}" 
                                class="btn-cancel">
                                Batal
                            </a>
                            <button type="submit" 
                                class="btn-update">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection