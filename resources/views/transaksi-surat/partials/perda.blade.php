<div class="tab-pane fade {{ request('tab') == 'perda' ? 'show active' : '' }}" id="perda">
    <h4>📋 Peraturan Daerah</h4>
    <div class="mb-3">
        <span class="stats-badge">
            <i class="fas fa-scroll me-1"></i>
            Jumlah PERDA: {{ $totalPerda }}
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>No</th>
                    <th>No. Agenda</th>
                    <th>No. PERDA</th>
                    <th>Tanggal</th>
                    <th>Perihal</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($perda as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->no_agenda }}</td>
                    <td>{{ $surat->no_perda }}</td>
                    <td>{{ $surat->tanggal ? $surat->tanggal->format('d/m/Y') : '-' }}</td>
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
                        <span class="text-truncate-custom" title="{{ $surat->catatan }}">
                            {{ $surat->catatan }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada PERDA</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div> 