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
                            <thead class="bg-blue-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">No Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Pengirim</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Tanggal SK</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Tanggal Terima</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Perihal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Disposisi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider font-bold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sk as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->pengirim }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal_surat->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->tanggal_terima->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->perihal }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="disposisi" onchange="showSubpoints(this)" style=" background-color: lightblue;">
                                                <option value="" style="background-color: rgb(108, 108, 243);">Pilih Disposisi</option>
                                                <option value="kabag" style=" background-color: rgb(252, 78, 78);">Perancangan perUU Kab/Kota</option>
                                                <option value="bankum" style=" background-color: green;">Kabag Bantuan dan Hukum</option>
                                                <option value="madya" style=" background-color: orange;">Perancangan PerUU Ahli Madya</option>
                                            </select>
                                            <div id="subpoints" class="mt-2 hidden">
                                                <div id="kabag" class="subpoint hidden">
                                                    <label><input type="radio" name="sub_kabag" value="analisis_hukum1"> Analisis Hukum 1</label><br>
                                                    <label><input type="radio" name="sub_kabag" value="analisis_hukum2"> Analisis Hukum 2</label><br>
                                                    <label><input type="radio" name="sub_kabag" value="analisis_hukum3"> Analisis Hukum 3</label>
                                                </div>
                                                <div id="bankum" class="subpoint hidden">
                                                    <label><input type="radio" name="sub_bankum" value="litigasi"> Litigasi </label><br>
                                                    <label><input type="radio" name="sub_bankum" value="nonlitigasi"> Non-Litigasi</label>
                                                    <label><input type="radio" name="sub_bankum" value="kasubag_tata_usaha"> Kasubag Tata Usaha</label>
                                                </div>
                                                <div id="madya" class="subpoint hidden">
                                                    <label><input type="radio" name="sub_madya" value="subker_penetapan"> Subker Penetapan</label><br>
                                                    <label><input type="radio" name="sub_madya" value="subker_pengaturan"> Subker Pengaturan</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="status" class="border border-gray-300 rounded-md">
                                                <option value="tersimpan" {{ $item->status == 'tersimpan' ? 'selected' : '' }}>Registrasi</option>
                                                <option value="terdisposisi" {{ $item->status == 'terdisposisi' ? 'selected' : '' }}>Terdisposisi</option>
                                                <option value="koreksi" {{ $item->status == 'koreksi' ? 'selected' : '' }}>Koreksi</option>
                                                <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="diambil" {{ $item->status == 'diambil' ? 'selected' : '' }}>Diterima/Diambil</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('draft-phd.sk.edit', $item->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form class="inline-block" action="{{ route('draft-phd.sk.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ml-2 text-red-600 hover:text-red-900">Hapus</button>
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
            const subpointsDiv = document.getElementById('subpoints');
            const kabagDiv = document.getElementById('kabag');
            const bankumDiv = document.getElementById('bankum');
            const madyaDiv = document.getElementById('madya');

            // Reset visibility
            subpointsDiv.classList.remove('hidden');
            kabagDiv.classList.add('hidden');
            bankumDiv.classList.add('hidden');
            madyaDiv.classList.add('hidden');

            // Show the relevant subpoints based on selection
            if (select.value === 'kabag') {
                kabagDiv.classList.remove('hidden');
            } else if (select.value === 'bankum') {
                bankumDiv.classList.remove('hidden');
            } else if (select.value === 'madya') {
                madyaDiv.classList.remove('hidden');
            }
        }
    </script>
@endsection