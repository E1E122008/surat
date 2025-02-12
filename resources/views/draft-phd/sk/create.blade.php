@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="text-2xl font-semibold mb-6">Tambah SK</h2>

                    @if ($errors->any())
                        <div class="mb-4">
                            <ul class="list-disc list-inside text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('draft-phd.sk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="no_surat" class="block text-sm font-medium text-gray-700">No Surat</label>
                                <input type="text" name="no_surat" id="no_surat" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       value="{{ old('no_surat') }}" required>
                            </div>

                            <div>
                                <label for="pengirim" class="block text-sm font-medium text-gray-700">Pengirim</label>
                                <input type="text" name="pengirim" id="pengirim" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       value="{{ old('pengirim') }}" required>
                            </div>

                            <div>
                                <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                                <input type="date" name="tanggal_surat" id="tanggal_surat" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       value="{{ old('tanggal_surat') }}" required>
                            </div>

                            <div>
                                <label for="tanggal_terima" class="block text-sm font-medium text-gray-700">Tanggal Terima</label>
                                <input type="date" name="tanggal_terima" id="tanggal_terima" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       value="{{ old('tanggal_terima') }}" required>
                            </div>

                            <div>
                                <label for="perihal" class="block text-sm font-medium text-gray-700">Perihal</label>
                                <input type="text" name="perihal" id="perihal" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       value="{{ old('perihal') }}" required>
                            </div>

                            <div>
                                <label for="lampiran" class="block text-sm font-medium text-gray-700">Lampiran</label>
                                <input type="file" name="lampiran" id="lampiran" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
                                       accept=".pdf,.doc,.docx">
                                <p class="mt-2 text-sm text-gray-500">PDF, DOC, atau DOCX (Maksimal 2MB)</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan SK
                            </button>
                            <a href="{{ route('draft-phd.sk.index') }}" class="ml-2 text-gray-600 hover:text-gray-900">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection