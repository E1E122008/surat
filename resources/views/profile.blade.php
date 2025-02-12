@extends('layouts.app')

@section('content')
    <h1>Profil Pengguna</h1>
    <p>Nama: {{ Auth::user()->name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>
    <!-- Tambahkan informasi lain yang diperlukan -->
@endsection
