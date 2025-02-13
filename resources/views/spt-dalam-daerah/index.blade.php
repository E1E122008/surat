@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">SPT Dalam Daerah</h2>
                        <div class="flex space-x-2">
                            <a href="{{ route('spt-dalam-daerah.create') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                + SPT Baru
                            </a>
                            <a href="{{ route('spt-dalam-daerah.export') }}" 
                               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Export Excel
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">No Surat</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Perihal</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Nama Petugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-black uppercase tracking-wider text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($spt as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->no_surat }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->tanggal->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $item->perihal }}</td>
                                        <td class="px-6 py-4 text-center">{{ $item->nama_petugas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex justify-center items-center">
                                                <a href="{{ route('spt-dalam-daerah.print', $item->id) }}" 
                                                class="text-green-600 hover:text-green-900 mr-2" title="Print">
                                                    <i class="fas fa-print" style="font-size: 1.5em;"></i>
                                                </a>
                                                <a href="{{ route('spt-dalam-daerah.edit', $item->id) }}" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-2" title="Edit">
                                                    <i class="fas fa-edit" style="font-size: 1.5em;"></i>
                                                </a>
                                                <form class="inline-block" action="{{ route('spt-dalam-daerah.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <i class="fas fa-trash" style="font-size: 1.5em;"></i>
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
@endsection