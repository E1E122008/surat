@extends('layouts.app')

@section('content')
    <div class="container">
        <strong><h2 class="mb-4">Rekapan Surat Masuk</h2></strong>

        <!-- Filter Waktu -->
        <form method="GET" action="{{ route('buku-agenda.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <label for="waktuSuratMasuk">Filter Waktu:</label>
                    <select name="waktuSuratMasuk" class="form-control">
                        <option value="minggu" {{ request('waktuSuratMasuk') == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan" {{ request('waktuSuratMasuk', 'bulan') == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun" {{ request('waktuSuratMasuk') == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary mt-4" type="submit">Tampilkan</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>No Agenda</th>
                        <th>No.Surat</th>
                        <th>Tanggal Terima</th>
                        <th>Pengirim</th>
                        <th>Perihal</th>
                        <th>Catatan</th>
                        <th>Disposisi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="dataSuratMasuk">
                    @forelse($suratMasuk as $index => $surat)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $surat->no_agenda }}</td>
                            <td>{{ $surat->no_surat }}</td>
                            <td>{{ $surat->tanggal_terima->format('d/m/y') }}</td>
                            <td>{{ $surat->pengirim }}</td>
                            <td>{{ $surat->perihal }}</td>
                            <td>{{ $surat->catatan }}</td>
                            <td>{{ $surat->disposisi }}</td>
                            <td>{{ $surat->status }}</td>
                            <td>
                                <a href="{{ route('surat-masuk.show', $surat->id) }}" class="btn btn-primary">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Tidak ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
@endsection
