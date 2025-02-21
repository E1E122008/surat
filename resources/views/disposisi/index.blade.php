@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Disposisi</strong></h2>
        </div>
        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg"    >
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Surat Masuk</h2>
                </div>
                <div class="overflow-x-auto">
                    <label for="filterDisposisi">Filter Disposisi: </label>
                    <select id="filterDisposisi" class="mr-2">
                        <option value="">Semua</option>
                        <option value="ktu">KTU</option>
                        <option value="sekretaris">Sekretaris</option>
                        <option value="kepala">Kepala</option>
                        <option value="kasubag">Kasubag</option>
                    </select>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="table-bordered">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Agenda</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($surat as $index => $surat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $surat->pengirim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($surat->disposisi == 'ktu')
                                            <span class="bg-ktu">KTU</span>
                                        @elseif($surat->disposisi == 'sekretaris')
                                            <span class="bg-sekretaris">Sekretaris</span>
                                        @elseif($surat->disposisi == 'kepala')
                                            <span class="bg-kepala">Kepala</span>
                                        @elseif($surat->disposisi == 'kasubag')
                                            <span class="bg-kasubag">Kasubag</span>
                                        @else
                                            {{ $surat->disposisi }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center items-center">
                                            <a href="{{ route('disposisi.edit', $surat->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Update Disposisi
                                            </a>
                                            <a href="{{ route('disposisi.detail', $surat->id) }}" class="btn btn-info btn-sm detail-btn">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            let table = $('.min-w-full').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
                "pageLength": 10
            });

            // Event ketika filter diubah
            $('#filterDisposisi').on('change', function() {
                let value = $(this).val();
                table.column(4).search(value).draw();  // Kolom ke-5 (index dimulai dari 0)
            });
        });
    </script>

    <style>
        tr:hover {
            background-color: #f2f2f2;
        }

        .bg-ktu {
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-sekretaris {
            background-color: rgba(0, 0, 255, 0.2);
            color: blue;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-kepala {
            background-color: rgba(255, 0, 0, 0.2);
            color: red;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-kasubag {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
@endsection


