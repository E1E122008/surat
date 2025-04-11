<div class="tab-pane fade {{ request('tab') == 'surat-keluar' ? 'show active' : '' }}" id="surat-keluar">
    <h4>📤 Surat Keluar</h4>
    <div class="mb-3">
        <span class="stats-badge">
            <i class="fas fa-paper-plane me-1"></i>
            Jumlah Surat: {{ $totalSuratKeluar }}
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
                    <th>Tujuan</th>
                    <th>Perihal</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->no_agenda }}</td>
                    <td>{{ $surat->no_surat }}</td>
                    <td>{{ $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-' }}</td>
                    <td>
                        <span class="text-truncate-custom" title="{{ $surat->tujuan }}">
                            {{ $surat->tujuan }}
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
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada surat keluar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 