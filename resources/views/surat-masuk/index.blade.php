@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Surat Umum</strong> / <span style="color: gray;"> Surat Masuk</span></h2>
        </div>
        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Surat Masuk</h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex items-center">
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari surat masuk..." 
                                   class="form-control"
                                   value="{{ request('search') }}"> 
                            <button type="submit" class="btn btn-primary ml-2">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                        <a href="{{ route('surat-masuk.create') }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus"></i> Surat Masuk
                        </a>
                        <a href="{{ route('surat-masuk.export') }}" 
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
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Pengirim</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Terima</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Catatan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($suratMasuk as $index => $surat)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->pengirim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                    
                                    
                                    <td class="px-6 py-4 whitespace-nowrap catatan-col">
                                        <div class="flex items-center space-x-2" data-surat-id="{{ $surat->id }}">
                                            <textarea name="" id="" cols="10" rows="2" class="catatan-textarea" placeholder="Tulis catatan..." readonly>{{ $surat->catatan }}</textarea>
                                            <button class="btn btn-sm btn-success" onclick="editCatatan({{ $surat->id }}, '{{ $surat->catatan }}')">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center"  >
                                        @if($surat->disposisi == 'Kabag Perancangan Per-UU kab/kota')
                                            <span class="bg-kepala">Kabag Perancangan Per-UU kab/kota</span>
                                        @elseif($surat->disposisi == 'Kabag Bantuan Hukum dan HAM')
                                            <span class="bg-sekretaris">Kabag Bantuan Hukum dan HAM</span>
                                        @elseif($surat->disposisi == 'Perancangan Per-UU Ahli Madya')
                                            <span class="bg-ktu">Perancangan Per-UU Ahli Madya</span>
                                        @elseif($surat->disposisi == 'Kasubag Tata Usaha')
                                            <span class="bg-kasubag">Kasubag Tata Usaha</span>
                                        @endif
                                        <a href="{{ route('disposisi.edit', $surat->id) }}" >
                                            <i class="fas fa-file-upload" style="color: rgb(0, 190, 0); font-size: 1.5em;"></i>
                                        </a>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($surat->status == 'tercatat')
                                            <span class="bg-tercatat">Tercatat</span>
                                        @elseif($surat->status == 'tersdisposisi')
                                            <span class="bg-tersdisposisi">Ters Disposisi</span>
                                        @elseif($surat->status == 'diproses')
                                            <span class="bg-diproses">Diproses</span>
                                        @elseif($surat->status == 'koreksi')
                                            <span class="bg-koreksi">Koreksi</span>
                                        @elseif($surat->status == 'diambil')
                                            <span class="bg-diambil">Diambil</span>
                                        @elseif($surat->status == 'selesai')
                                            <span class="bg-selesai">Selesai</span>
                                        @endif
                                        <button onclick="openStatusModal({{ $surat->id }}, '{{ $surat->status }}')" class="btn btn-light btn-sm ms-2" style="background-color: white; border: 1px solid #dee2e6;">
                                            <i class="fas fa-sync-alt" style="color: #29fd0d;"></i>
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex justify-center items-center">
                                            <a href="{{ route('surat-masuk.detail', $surat->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>    
                                            
                                            
                                            
                                            <form id="delete-form-{{ $surat->id }}" action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $surat->id }})">
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
                    {{ $suratMasuk->links() }}
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Surat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">    
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="tercatat">Tercatat</option>
                                <option value="tersdisposisi">Ters Disposisi</option>
                                <option value="diproses">Diproses</option>
                                <option value="koreksi">Koreksi</option>
                                <option value="diambil">Diambil</option>
                                <option value="selesai">Selesai</option>
                            </select>
                        </div>  
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .bg-ktu {
            background-color: rgba(255, 0, 0, 0.2);
            color: red;
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
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
            padding: 2px 5px;
            border-radius: 3px;

            
        }   

        .bg-kasubag {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-tercatat     {
            background-color: #D1D5DB;
            color: #374151;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-tersdisposisi {
            background-color: rgba(0, 0, 255, 0.2);
            color: blue;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-diproses {
            background-color: #FEF08A;
            color: #713F12;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-koreksi {
            background-color: rgba(255, 165, 0, 0.2);
            color: orange;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-diambil {
            background-color: rgba(0, 255, 0, 0.2);
            color: green;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .bg-selesai {
            background-color: #D8B4FE;
            color: #4C1D95;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>                

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        function showSubpoints(select) {
            let selectedValue = select.value;
            let subpointSelect = select.parentElement.querySelector('.subpoint');
            
            let subpoints = {
                'kabag': ['Analisis Hukum ahli muda wilayah 1', 'Analisis Hukum ahli muda wilayah 2','Analisis Hukum ahli muda wilayah 3'],
                'bankum': ['Litigasi', 'Non-litigasi', 'Kasubag Tata Usaha'],
                'madya': ['Subker Penetapan', 'Subker Pengaturan']
            };

            subpointSelect.innerHTML = '<option value="">Pilih Subpoint</option>';

            if (selectedValue && subpoints[selectedValue]) {
                subpoints[selectedValue].forEach(sp => {
                    let option = document.createElement("option");
                    option.value = sp.toLowerCase().replace(/\s+/g, '_');
                    option.textContent = sp;
                    subpointSelect.appendChild(option);
                });
            }

            subpointSelect.style.display = selectedValue ? 'block' : 'none';
        }

        function openStatusModal(id, currentStatus) {
            document.getElementById('statusForm').action = `/surat-masuk/${id}/update-status`;
            document.getElementById('status').value = currentStatus;
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        function searchTable() {
            const input = document.getElementById('search');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('table');
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const td = tr[i].getElementsByTagName('td');
                let found = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        const txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }
         
        // Fungsi untuk konfirmasi hapus dengan SweetAlert2
        function confirmDelete(deleteUrl) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                showClass: {
                    popup: 'animate__animated animate__bounceIn'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tampilkan loading state dengan progress bar
                    Swal.fire({
                        title: 'Menghapus data...',
                        html: 'Tunggu sebentar, sedang diproses <b></b> detik.',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading();
                            const timer = Swal.getHtmlContainer().querySelector('b');
                            let timeLeft = 2;
                            const interval = setInterval(() => {
                                timer.textContent = timeLeft;
                                timeLeft--;
                                if (timeLeft < 0) clearInterval(interval);
                            }, 1000);
                        }
                    }).then(() => {
                        // Setelah loading selesai, lakukan penghapusan
                        window.location.href = deleteUrl;
                    });
                }
            });
        }

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

        // Fungsi untuk menampilkan alert error
        function showError(message) {
            Swal.fire({
                title: 'Error!',
                text: message,
                icon: 'error',
                showClass: {
                    popup: 'animate__animated animate__shakeX'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                }
            });
        }

        @if(session('success'))
        
            Swal.fire({
                title: "Berhasil!",
                text: "{{ session('success') }}",
                icon: "success",
                showConfirmButton: false,
                timer: 2000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#10B981',
                color: '#ffffff'
            });
        
        @endif

        @if(session('error'))
        
            Swal.fire({
                title: "Error!",
                text: "{{ session('error') }}",
                icon: "error",
                showConfirmButton: false,
                timer: 3000,
                toast: true,
                position: "top-end",
                showClass: {
                    popup: 'animate__animated animate__fadeInRight'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight'
                },
                background: '#EF4444',
                color: '#ffffff'
            });
            
        @endif

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
                fetch(`/surat-masuk/${suratId}/update-catatan`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        catatan: textarea.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess('Catatan berhasil diperbarui');
                    } else {
                        showError('Gagal memperbarui catatan');
                        textarea.value = currentCatatan; // Revert to original value
                    }
                })
                .catch(error => {
                    showError('Terjadi kesalahan sistem');
                    textarea.value = currentCatatan; // Revert to original value
                });
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