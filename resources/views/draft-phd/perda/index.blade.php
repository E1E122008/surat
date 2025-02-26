@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Registrasi Draft PHD </strong> / <span style="color: gray;"> Peraturan Daerah</span></h2>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Peraturan Daerah</h2>
                    <div class="flex space-x-2">
                        <form action="{{ route('draft-phd.perda.index') }}" method="GET" class="flex space-x-2">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Cari..." 
                                   class="form-control px-4 py-2 border rounded-lg">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </form>
                        <a href="{{ route('draft-phd.perda.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Perda
                        </a>
                        <a href="{{ route('draft-phd.perda.export') }}" class="btn btn-success">
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
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Disposisi</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">   
                            @foreach($perdas as $index => $perda)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $perda->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $perda->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $perda->pengirim }}</td> 
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{($perda->tanggal_terima)->format('d/m/Y') }}</td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($perda->disposisi)
                                            @php
                                                $disposisiParts = explode('|', $perda->disposisi);
                                                $mainDisposisi = trim($disposisiParts[0]);
                                            @endphp
                                            <span class="bg-{{ strtolower(str_replace(' ', '-', $mainDisposisi)) }}">
                                                {{ $mainDisposisi }}
                                            </span>
                                            @if(count($disposisiParts) > 1)
                                                <br>
                                                <small class="text-muted">
                                                    @foreach(array_slice($disposisiParts, 1) as $part)
                                                        {{ trim($part) }}<br>
                                                    @endforeach
                                                </small>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>   
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                        @if($perda->status == 'tercatat')
                                            <span class="bg-tercatat">Tercatat</span>
                                        @elseif($perda->status == 'tersdisposisi')
                                            <span class="bg-tersdisposisi">Ters Disposisi</span>
                                        @elseif($perda->status == 'diproses')
                                            <span class="bg-diproses">Diproses</span>
                                        @elseif($perda->status == 'koreksi')
                                            <span class="bg-koreksi">Koreksi</span>
                                        @elseif($perda->status == 'diambil')
                                            <span class="bg-diambil">Diambil</span>
                                        @elseif($perda->status == 'selesai')
                                            <span class="bg-selesai">Selesai</span>
                                        @endif
                                        
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button type="button" class="btn btn-light btn-sm" onclick="openDisposisiModal({{ $perda->id }})" title="Disposisi">
                                            <i class="fas fa-sync-alt" style="color: #29fd0d;"></i>
                                        </button>
                                        <form onclick="openStatusModal({{ $perda->id }})" method="POST" class="inline" title="Update Status">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('draft-phd.perda.detail', $perda->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <form class="inline-block" id="delete-form-{{ $perda->id }}" action="{{ route('draft-phd.perda.destroy', $perda->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $perda->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach 
                        </tbody>
                    </table>
                </div> 
                <div class="mt-4">
                    {{ $perdas->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Perda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" style="border: 1px solid #e5e7eb;">
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
    <div class="modal fade" id="disposisiModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Disposisi Perda</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="disposisiForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="disposisi" class="form-label">Tujuan Disposisi</label>
                            <select class="form-select" id="disposisi" name="disposisi" required>
                                <option value="">Pilih Tujuan Disposisi</option>
                                <option value="Kabag Perancangan Per-UU kab/kota">Kabag Perancangan Per-UU kab/kota</option>
                                <option value="Kabag Bantuan Hukum dan HAM">Kabag Bantuan Hukum dan HAM</option>
                                <option value="Perancangan Per-UU Ahli Madya">Perancangan Per-UU Ahli Madya</option>
                                <option value="Kasubag Tata Usaha">Kasubag Tata Usaha</option>
                            </select>
                        </div>

                        <div class="mb-3" id="subDisposisiContainer" style="display: none;">
                            <label for="sub_disposisi" class="form-label">Diteruskan Kepada</label>
                            <select class="form-select" id="sub_disposisi" name="sub_disposisi">
                                <option value="">Pilih Tujuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_disposisi" class="form-label">Tanggal Disposisi</label>
                            <input type="date" class="form-control" id="tanggal_disposisi" name="tanggal_disposisi" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
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

        // Fungsi untuk menghapus surat Peraturan Daerah
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus surat Peraturan Daerah ini?')) {
                document.getElementById('delete-form-' + id).submit();
            }
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

        function openStatusModal(id, currentStatus) {
            console.log('Opening modal for Perda ID:', id); // Debug log
            const modal = document.getElementById('statusModal');
            const form = document.getElementById('statusForm');
            form.action = `/draft-phd/perda/${id}/update-status`;
            document.getElementById('status').value = currentStatus;
            
            // Pastikan Bootstrap Modal tersedia
            if (typeof bootstrap !== 'undefined') {
                new bootstrap.Modal(modal).show();
            } else {
                console.error('Bootstrap Modal tidak tersedia');
            }
        }

        document.getElementById('statusForm').addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit(); // Submit form secara normal
        });

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
                fetch(`/draft-phd/perda/${suratId}/update-catatan`, {
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
                    if   (data.success) {
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

        function showError(message) {
            Swal.fire({
                title: 'Oops...',
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
            showSuccess('{{ session('success') }}');
        @endif

        const subDisposisiOptions = {
            'Kabag Perancangan Per-UU kab/kota': [
                'Belum/Tidak diteruskan',
                'Analisis Hukum Wilayah 1',
                'Analisis Hukum Wilayah 2',
                'Analisis Hukum Wilayah 3'
            ],
            'Kabag Bantuan Hukum dan HAM': [
                'Belum/Tidak diteruskan',
                'Analisis Hukum Litigasi',
                'Analisis Hukum Non-Litigasi',
                'Kasubag Tata Usaha'
            ],
            'Perancangan Per-UU Ahli Madya': [
                'Belum/Tidak diteruskan',
                'Subker Penetapan',
                'Subker Pengaturan'
            ],
            'Kasubag Tata Usaha': [
                'Belum/Tidak diteruskan'
            ]
        };

        function openDisposisiModal(id) {
            const form = document.getElementById('disposisiForm');
            form.action = `/draft-phd/perda/${id}/disposisi`;
            form.reset();
            document.getElementById('subDisposisiContainer').style.display = 'none';
            new bootstrap.Modal(document.getElementById('disposisiModal')).show();
        }

        // Event listener untuk perubahan disposisi
        document.getElementById('disposisi').addEventListener('change', function() {
            const selectedDisposisi = this.value;
            const subDisposisiContainer = document.getElementById('subDisposisiContainer');
            const subDisposisiSelect = document.getElementById('sub_disposisi');
            
            subDisposisiSelect.innerHTML = '<option value="">Pilih Tujuan</option>';
            
            if (selectedDisposisi && subDisposisiOptions[selectedDisposisi]) {
                subDisposisiContainer.style.display = 'block';
                
                subDisposisiOptions[selectedDisposisi].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    subDisposisiSelect.appendChild(optionElement);
                });
                
                subDisposisiSelect.required = (selectedDisposisi !== 'Kasubag Tata Usaha');
            } else {
                subDisposisiContainer.style.display = 'none';
                subDisposisiSelect.required = false;
            }
        });

        // Perbaikan pada form submit handler
        document.getElementById('disposisiForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const disposisi = document.getElementById('disposisi').value;
            const subDisposisi = document.getElementById('sub_disposisi').value;
            const tanggalDisposisi = document.getElementById('tanggal_disposisi').value;
            
            // Validasi form
            if (!disposisi) {
                alert('Silakan pilih tujuan disposisi');
                return;
            }
            
            if (disposisi !== 'Kasubag Tata Usaha' && !subDisposisi) {
                alert('Silakan pilih sub disposisi');
                return;
            }
            
            if (!tanggalDisposisi) {
                alert('Silakan isi tanggal disposisi');
                return;
            }
            
            // Jika semua validasi passed, submit form
            this.submit();
        });
    </script>  
    
    <style>
        .bg-tercatat {
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

        .bg-ditolak {
            background-color: #FCA5A5;
            color: #7F1D1D;
            padding: 2px 5px;
            border-radius: 3px;
        }

        .form-select {
            background-color: white !important;
            border: 1px solid #ced4da !important;
        }
    </style>
@endsection