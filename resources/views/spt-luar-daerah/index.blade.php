@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Surat Perintah Tugas</strong> / <span style="color: gray;"> SPT Luar Daerah</span></h2>
        </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">SPT Luar Daerah</h2>
                        <div class="flex space-x-2">
                            <form action="{{ route('spt-luar-daerah.index') }}" method="GET" class="flex space-x-2">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari..." 
                                       class="form-control px-4 py-2 border rounded-lg">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </form>
                            <a href="{{ route('spt-luar-daerah.create') }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus"></i> SPT Baru
                            </a>
                            <a href="{{ route('spt-luar-daerah.export') }}" 
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
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($spt as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->no_agenda }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tujuan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->perihal }}</td>
                                        <td class="px-6 py-4 text-center">{{ $item->nama_petugas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button onclick="window.location.href='{{ asset('storage/' . $item->lampiran) }}'" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Lampiran
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center items-center">
                                                <a href="{{ route('spt-luar-daerah.edit', $item->id) }}" 
                                                class="btn btn-info btn-sm edit-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form class="d-inline" id="delete-form-{{ $item->id }}" action="{{ route('spt-luar-daerah.destroy', $item->id) }}" method="POST" >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})" title="Hapus">
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
                        {{ $spt->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus SPT Luar Daerah ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.min-w-full').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "pageLength":15 ,
            });
        });
    </script>
@endsection