@extends('layouts.app')

@section('content')
    <div>
        <div class="container">
            <h2 class="header h2"><strong>📂 Registrasi Draft PHD </strong> / <span style="color: gray;"> Peraturan Gubernur</span></h2>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Peraturan Gubernur</h2>
                    <div class="flex space-x-2">
                        <a href="{{ route('draft-phd.pergub.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah pergub
                        </a>
                        <a href="{{ route('draft-phd.pergub.export') }}" 
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
                            @foreach($pergubs as $index => $pergub)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pergub->no_agenda }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pergub->no_surat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pergub->pengirim }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{($pergub->tanggal_surat)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{($pergub->tanggal_terima)->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">{{ $pergub->perihal }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button onclick="window.location.href='{{ asset('storage/' . $pergub->lampiran) }}'" class="btn btn-primary">
                                            <i class="fas fa-eye"></i> Lihat Lampiran
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap catatan-col">
                                        <div class="flex items-center space-x-2" data-surat-id="{{ $pergub->id }}">
                                            <textarea name="" id="" cols="10" rows="2" class="catatan-textarea" placeholder="Tulis catatan..." readonly>{{ $pergub->catatan }}</textarea>
                                            <button class="btn btn-sm btn-success" onclick="editCatatan({{ $pergub->id }}, '{{ $pergub->catatan }}')">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <select name="disposisi" onchange="showSubpoints(this)" class="disposisi-dropdown text-center" style="background-color: lightblue; border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                            <option value="">Pilih Disposisi</option>
                                            <option value="kabag">Perancangan perUU Kab/Kota</option>
                                            <option value="bankum">Kabag Bantuan dan Hukum</option> 
                                            <option value="madya">Perancangan PerUU Ahli Madya</option>
                                        </select>
                                        <select name="subpoint" class="subpoint text center" style="display: none; margin-top: 5px; background-color: rgb(183, 223, 236); border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                            <option value="">Pilih Subpoint</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <select name="status" class="status-dropdown text-center" style="background-color: lightgreen; border-radius: 5px; border: 1px solid #ccc; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);">
                                            <option value="">Pilih Status</option>
                                            <option value="tercatat">Tercatat</option>
                                            <option value="terdisposisi">Terdisposisi</option>
                                            <option value="diproses">Diproses</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('draft-phd.pergub.edit', $pergub->id) }}" class="btn btn-info btn-sm edit-btn">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form class="inline-block" id="delete-form-{{ $pergub->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $pergub->id }})">
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
                    {{ $pergubs->links() }}
                </div>
            </div>  
        </div>
    </div>
    <script>
        function showSubpoints(select) {
            let selectedValue = select.value;
            let subpointSelect = select.parentElement.querySelector('.subpoint');
            
            let subpoints = {
                kabag: ['Perancangan perUU Kab/Kota'],
                bankum: ['Kabag Bantuan dan Hukum'],
                madya: ['Perancangan PerUU Ahli Madya']
            };
            
            if (subpoints[selectedValue]) { 
                subpointSelect.innerHTML = '';
                subpoints[selectedValue].forEach(subpoint => {
                    let option = document.createElement('option');
                    option.value = subpoint;
                    option.textContent = subpoint;
                    subpointSelect.appendChild(option); 
                });
            } else {
                subpointSelect.innerHTML = '<option value="">Pilih Subpoint</option>';
            }
        }

        function editCatatan(suratId, catatan) {
            const container = document.querySelector(`[data-surat-id="${suratId}"]`);
            const textarea = container.querySelector('.catatan-textarea');

            textarea.readOnly = !textarea.readOnly;

            if (!textarea.readOnly) {
                textarea.focus();
                container.querySelector('.btn-success i').classList.remove('fa-sync-alt');
                container.querySelector('.btn-success i').classList.add('fa-save');
            } else {
                container.querySelector('.btn-success i').classList.remove('fa-save');
                container.querySelector('.btn-success i').classList.add('fa-sync-alt');

                fetch(`/draft-phd/pergub/${suratId}/update-catatan`, {
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
                    console.log('Response:', response); 
                    return response.json();
                })
                .then(data => {
                    console.log('Data:', data);
                    if (data.success) {
                        showSuccess('Catatan berhasil diperbarui');
                    } else {
                        showError('Gagal memperbarui catatan');
                        textarea.value = catatan;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Terjadi kesalahan sistem');
                    textarea.value = catatan;
                });
            }
        }

        function showSuccess(message) {
            Swal.fire({
                title: 'Berhasil!',
                text: message,
                icon: 'success',
                showClass: {
                    popup: 'animate__animated animate__shakeX'  
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOut'
                },
                timer: 2000,
                timerProgressBar: true
            });
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
    </script>
    
@endsection 