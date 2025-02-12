@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Agenda</h1>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <label for="date-range">Pilih Rentang Waktu:</label>
            <select id="date-range" name="date-range">
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
                <option value="yearly">Tahunan</option>
            </select>

            <label for="status">Status:</label>
            <select id="status" name="status">
                <option value="all">Semua</option>
                <option value="completed">Selesai</option>
                <option value="pending">Belum Selesai</option>
            </select>

            <button type="button" id="filter-button">Terapkan Filter</button>
        </div>

        <!-- Data Section -->
        <div class="data-section">
            <h2>Data Surat Masuk dan Keluar</h2>
            <div id="incoming-letters">
                <!-- Data surat masuk akan ditampilkan di sini -->
            </div>
            <div id="outgoing-letters">
                <!-- Data surat keluar akan ditampilkan di sini -->
            </div>

            <h2>Data SPPD</h2>
            <div id="sppd-internal">
                <!-- Data SPPD dalam akan ditampilkan di sini -->
            </div>
            <div id="sppd-external">
                <!-- Data SPPD luar akan ditampilkan di sini -->
            </div>

            <h2>Data SPT</h2>
            <div id="spt-internal">
                <!-- Data SPT dalam akan ditampilkan di sini -->
            </div>
            <div id="spt-external">
                <!-- Data SPT luar akan ditampilkan di sini -->
            </div>
        </div>
    </div>
@endsection
