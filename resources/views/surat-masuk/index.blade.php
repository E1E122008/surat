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
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal Terima</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Lampiran</th>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $surat->perihal }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="window.location.href='{{ asset('storage/' . $surat->lampiran) }}'" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Lampiran
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap catatan-col">
                                        <div class="flex items-center space-x-2" data-surat-id="{{ $surat->id }}">
                                            <textarea name="" id="" cols="10" rows="2" class="catatan-textarea" placeholder="Tulis catatan..." readonly>{{ $surat->catatan }}</textarea>
                                            <button class="btn btn-sm btn-success" onclick="editCatatan({{ $surat->id }}, '{{ $surat->catatan }}')">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $surat->disposisi }} </td>

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
                                            <a href="{{ route('surat-masuk.edit', $surat->id) }}" class="btn btn-info btn-sm edit-btn">
                                                <i class="fas fa-edit"></i>
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
@endsection