@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Registrasi Draft PHD </strong> / <span style="color: gray;"> Surat Keputusan</span></h2>
        </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Surat Keputusan</h2>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('draft-phd.sk.index') }}" method="GET" class="flex items-center">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari SK..." 
                                       class="form-control px-4 py-2 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                                <button type="submit" class="btn btn-primary ml-2">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </form>
                            <a href="{{ route('draft-phd.sk.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah SK
                            </a>
                            <a href="{{ route('draft-phd.sk.export') }}" class="btn btn-success">
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
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Terima</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sk as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_agenda }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->pengirim }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal_terima->format('d/m/Y') }}</td>

                                        <td class="px-6 py-4 whitespace-nowrap catatan-col">
                                            <div class="flex items-center space-x-2" data-surat-id="{{ $item->id }}">
                                                <textarea name="" id="" cols="10" rows="2" class="catatan-textarea" placeholder="Tulis catatan..." readonly>{{ $item->catatan }}</textarea>
                                                <button class="btn btn-sm btn-success" onclick="editCatatan({{ $item->id }}, '{{ $item->catatan }}')">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <select name="disposisi" onchange="showSubpoints(this)" class="disposisi-dropdown text-center" style="background-color: lightblue; border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                                <option value="">Pilih Disposisi</option>
                                                <option value="kabag">Perancangan perUU Kab/Kota</option>
                                                <option value="bankum">Kabag Bantuan dan Hukum</option>
                                                <option value="madya">Perancangan PerUU Ahli Madya</option>
                                            </select>
                                            <select name="subpoint" class="subpoint text center" style="display: none; margin-top: 5px; background-color: rgb(183, 223, 236); border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                                <option value="">Pilih Subpoint</option>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <select name="status" class="status-dropdown text-center" style="background-color: lightgreen; border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                                <option value="">Pilih Status</option>
                                                <option value="tercatat">Tercatat</option>
                                                <option value="terdisposisi">Terdisposisi</option>
                                                <option value="diproses">Diproses</option>
                                                <option value="koreksi">Koreksi</option>
                                                <option value="selesai">Selesai</option>
                                                <option value="diambil">Diambil</option>
                                            </select>
                                        </td>   
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center items-center">
                                                <a href="{{ route('draft-phd.sk.detail', $item->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                <form class="d-inline" id="delete-form-{{ $item->id }}" action="{{ route('draft-phd.sk.destroy', $item->id) }}" method="POST">
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
                        {{ $sk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
    // Fungsi untuk menampilkan alert sukses
    function showSuccess(message) {
        Swal.fire({
            title: 'Berhasil!',
            text: message,
            icon: 'success',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            timer: 2000,
            timerProgressBar: true
        });
    }

    function editCatatan(suratId, currentCatatan) {
        const container = document.querySelector(`[data-surat-id="${suratId}"]`);
        const textarea = container.querySelector('.catatan-textarea');
        
        // Toggle readonly state
        textarea.readOnly = !textarea.readOnly;
        
        if (!textarea.readOnly) {
            // Enter edit mode
            textarea.focus();
            container.querySelector('.btn-success i').classList.remove('fa-sync-alt');
            container.querySelector('.btn-success i').classList.add('fa-save');
        } else {
            // Save mode
            container.querySelector('.btn-success i').classList.remove('fa-save');
            container.querySelector('.btn-success i').classList.add('fa-sync-alt');
            
            // Send AJAX request to update catatan
            fetch(`/draft-phd/sk/${suratId}/update-catatan`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    catatan: textarea.value
                })
            })
            .then(response => {
                console.log('Response:', response); // Log the response for debugging
                return response.json();
            })
            .then(data => {
                console.log('Data:', data); // Log the data returned from the server
                if (data.success) {
                    showSuccess('Catatan berhasil diperbarui');
                } else {
                    showError('Gagal memperbarui catatan');
                    textarea.value = currentCatatan; // Revert to original value
                }
            })
            .catch(error => {
                console.error('Error:', error); // Log any errors
                showError('Terjadi kesalahan sistem');
                textarea.value = currentCatatan; // Revert to original value
            });
        }
    }

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus Surat Keputusan ini?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }

    </script>

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
        });
    </script>   

    
    
@endsection