@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Surat Perintah Perjalanan Dinas</strong> / <span style="color: gray;"> SPPD Luar Daerah</span></h2>
        </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">SPPD Luar Daerah</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('sppd-luar-daerah.create') }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> SPPD Baru
                            </a>
                            <a href="{{ route('sppd-luar-daerah.export') }}" 
                               class="btn btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-bordered">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Agenda</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tujuan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama yang di Tugaskan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sppd as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_agenda }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->perihal }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->nama_petugas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button onclick="window.location.href='{{ asset('storage/' . $item->lampiran) }}'" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Lampiran
                                            </button>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center">
                                                <a href="{{ route('sppd-luar-daerah.print', $item->id) }}" 
                                                class="btn btn-success btn-sm" title="Print">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('sppd-luar-daerah.edit', $item->id) }}" 
                                                class="btn btn-info btn-sm edit-btn">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form class="inline-block" action="{{ route('sppd-luar-daerah.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sppd->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection