@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold">Update Disposisi</h2>
                </div>
                <div class="p-6 bg-white border-b border-gray-200"> 
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form action="{{ route('disposisi.update', $surat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <label class="block text-gray-600 font-medium mb-2">Pilih Disposisi:</label>

                        <div class="space-y-3">
                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="disposisi" value="kab" required class="mr-2">
                                <span class="text-blue-500 text-lg">📂</span>
                                <span>Kabag Perancangan Per-UU kab/kota</span>
                            </label>

                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="disposisi" value="bankum" required class="mr-2">
                                <span class="text-green-500 text-lg">👨‍💼</span>
                                <span>Kabag Bantuan Hukum dan HAM</span>
                            </label>

                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="disposisi" value="madya" required class="mr-2">
                                <span class="text-purple-500 text-lg">📝</span>
                                <span>Perancangan Per-UU Ahli Madya</span>
                            </label>

                            <label class="flex items-center space-x-2 p-3 border rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="disposisi" value="kasubag" required class="mr-2">
                                <span class="text-purple-500 text-lg">📝</span>
                                <span>Kasubag Tata Usaha</span>
                            </label>
                        </div>
                        
                        <div class="mt-4">
                        <button type="button" onclick="window.location.href='{{ route('surat-masuk.index') }}'" class="w-full bg-gray-400 text-white p-3 rounded-lg hover:bg-gray-500 transition text-center">Batal</button>
                        <button type="submit" class="mt-4 w-full bg-blue-600 text-white p-3 rounded-lg hover:bg-blue-700 transition">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
