@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Status Surat Masuk</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('surat-masuk.update-status', $surat->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <label class="block text-gray-600 font-medium mb-2">Pilih Status:</label>
                        <div class="space-y-3">
                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="tercatat" required class="mr-2">
                    <span class="text-blue-500 text-lg">📝</span>
                    <span>Tercatat</span>
                </label>

                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="tersdisposisi" required class="mr-2">
                    <span class="text-green-500 text-lg">📬</span>
                    <span>Ters Disposisi</span>
                </label>

                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="diproses" required class="mr-2">
                    <span class="text-purple-500 text-lg">🔄</span>
                    <span>Diproses</span>
                </label>

                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="koreksi" required class="mr-2">
                    <span class="text-red-500 text-lg">✏️</span>
                    <span>Koreksi</span>
                </label>

                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="selesai" required class="mr-2">
                    <span class="text-green-600 text-lg">✅</span>
                    <span>Selesai</span>
                </label>

                <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                    <input type="radio" name="status" value="diambil" required class="mr-2">
                    <span class="text-orange-500 text-lg">📥</span>
                    <span>Diambil</span>
                </label>
                </div>
            </div>
            <div class="mt-4">
                <button type="button" onclick="window.location.href='{{ route('surat-masuk.index') }}'" class="w-full bg-gray-400 text-white p-3 rounded-lg hover:bg-gray-500 transition text-center">Batal</button>
                <button type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition">Simpan</button>
            </div>
        </div>
    </div>
@endsection
