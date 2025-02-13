@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Surat Keputusan</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('draft-phd.sk.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + Tambah SK
                            </a>
                            <a href="{{ route('draft-phd.sk.export') }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-200">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">No Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Pengirim</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tanggal SK</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Tanggal Terima</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Perihal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Disposisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sk as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->pengirim }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal_terima->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->perihal }}</td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <select name="disposisi" onchange="showSubpoints(this)" class="disposisi-dropdown text-center"  style="background-color: lightblue;">
                                                <option value="">Pilih Disposisi</option>
                                                <option value="kabag">Perancangan perUU Kab/Kota</option>
                                                <option value="bankum">Kabag Bantuan dan Hukum</option>
                                                <option value="madya">Perancangan PerUU Ahli Madya</option>
                                            </select>
                                            <select name="subpoint" class="subpoint text center" style="display: none; margin-top: 5px; background-color: lightblue;">
                                                <option value="">Pilih Subpoint</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap catatan-col">
                                            <textarea name="catatan" rows="3" class="catatan-textarea border border-gray-300 rounded-md" placeholder="Tulis catatan...">{{ old('catatan', $item->catatan) }}</textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('draft-phd.sk.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form class="inline-block" action="{{ route('draft-phd.sk.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900">
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
                        {{ $sk->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSubpoints(select) {
            let selectedValue = select.value;
            let subpointSelect = select.parentElement.querySelector('.subpoint');
            
            let subpoints = {
                'kabag': ['Analisis Hukum 1', 'Analisis Hukum 2','Analisis Hukum 3'],
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
    </script>
@endsection