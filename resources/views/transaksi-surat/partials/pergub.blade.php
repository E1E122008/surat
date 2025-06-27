<div class="tab-pane fade {{ request('tab') == 'pergub' ? 'show active' : '' }}" id="pergub">
    <h4>📋 Peraturan Gubernur</h4>
    <div class="mb-3">
        <span class="stats-badge">
            <i class="fas fa-scroll me-1"></i>
            Jumlah PERGUB: {{ $totalPergub }}
        </span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">No. Agenda</th>
                    <th class="text-center">No. PERGUB</th>
                    <th class="text-center">Pengirim</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Perihal</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Disposisi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pergub as $index => $surat)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $surat->no_agenda }}</td>
                    <td class="text-center">{{ $surat->no_surat }}</td>
                    <td class="text-center">{{ $surat->pengirim }}</td>
                    <td class="text-center">{{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}</td>
                    <td class="text-center">
                        <span class="perihal-truncate" title="{{ $surat->perihal }}">
                            {{ $surat->perihal }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-{{ $surat->status === 'Selesai' ? 'success' : 'warning' }}">
                            {{ $surat->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="text-truncate-custom" title="{{ $surat->disposisi }}">
                            {{ $surat->disposisi }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada PERGUB</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        @if(isset($pergub) && method_exists($pergub, 'links'))
            <div class="mt-3 d-flex justify-content-center">
                {{ $pergub->links() }}
            </div>
        @endif
    </div>
</div> 