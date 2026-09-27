<div class="tab-pane fade {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'show active' : '' }}" id="surat-masuk">
    <div class="table-responsive" style="overflow-x:auto; -webkit-overflow-scrolling:touch;">
        <table class="table arsip-table">
            <thead>
                <tr>
                    <th class="text-center" style="width:48px;">NO</th>
                    <th>NO. SURAT</th>
                    <th>PENGIRIM</th>
                    <th class="text-center" style="width:130px;">TANGGAL TERIMA</th>
                    <th>PERIHAL</th>
                    <th class="text-center" style="width:110px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuk as $index => $surat)
                    @php
                        $lampiranData = [];
                        if ($surat->file_surat) {
                            foreach (explode(',', $surat->file_surat) as $f) {
                                $f = trim($f);
                                if ($f) {
                                    $lampiranData[] = [
                                        'name' => basename($f),
                                        'url' => asset('storage/' . $f),
                                    ];
                                }
                            }
                        }
                        $detailData = json_encode(
                            [
                                'no_surat' => $surat->no_surat,
                                'no_agenda' => $surat->no_agenda,
                                'tanggal' => $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-',
                                'pengirim' => $surat->pengirim,
                                'perihal' => $surat->perihal,
                                'disposisi' => $surat->disposisi ?: '-',
                                'isAdmin' => auth()->user()->role === 'admin',
                                'lampiran' => auth()->user()->role === 'admin' ? $lampiranData : [],
                            ],
                            JSON_HEX_QUOT | JSON_HEX_APOS,
                        );
                    @endphp
                    <tr>
                        <td class="text-center">
                            {{ $index + 1 + ($suratMasuk->currentPage() - 1) * $suratMasuk->perPage() }}</td>
                        <td style="white-space:nowrap;">{{ $surat->no_surat }}</td>
                        <td>{{ $surat->pengirim }}</td>
                        <td class="text-center" style="white-space:nowrap;">
                            {{ $surat->tanggal_terima ? $surat->tanggal_terima->format('d/m/Y') : '-' }}
                        </td>
                        <td>
                            <span class="perihal-cell d-block"
                                title="{{ $surat->perihal }}">{{ $surat->perihal }}</span>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-light"
                                style="border-radius:20px; font-size:.8rem; padding:5px 14px; border:1px solid #e2e8f0;"
                                onclick="openDetail({{ $detailData }})">
                                <i class="fas fa-eye me-1"></i> Detail
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <i class="fas fa-inbox me-2"></i>Tidak ada data surat masuk
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if (isset($suratMasuk) && method_exists($suratMasuk, 'links'))
        <div class="mt-3 d-flex justify-content-center">
            {{ $suratMasuk->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>
