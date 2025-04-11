<div class="tab-pane fade {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'show active' : '' }}" id="surat-masuk">
    <h4>📥 Surat Masuk</h4>
    <div class="mb-3">
        <span class="stats-badge">
            <i class="fas fa-envelope me-1"></i>
            Jumlah Surat: {{ $totalSuratMasuk }}
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>No. Agenda</th>
                    <th>No. Surat</th>
                    <th>Tanggal</th>
                    <th>Pengirim</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Disposisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuk as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->no_agenda }}</td>
                    <td>{{ $surat->no_surat }}</td>
                    <td>{{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="text-truncate-custom" title="{{ $surat->pengirim }}">
                            {{ $surat->pengirim }}
                        </span>
                    </td>
                    <td>
                        <span class="perihal-truncate" title="{{ $surat->perihal }}">
                            {{ $surat->perihal }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-{{ $surat->status === 'Selesai' ? 'success' : 'warning' }}">
                            {{ $surat->status }}
                        </span>
                    </td>
                    <td>
                        <span class="disposisi-truncate" title="{{ $surat->disposisi }}">
                            {{ $surat->disposisi }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada surat masuk</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 