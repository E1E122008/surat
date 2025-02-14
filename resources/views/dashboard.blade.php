@extends('layouts.app')

@section('content')
    <!-- Welcome Section -->
    <div class="dashboard-welcome mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Selamat Datang di Sistem Manajemen Surat</h2>
                <p class="text-muted">Pemerintah Sulawesi Tenggara</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="current-date">
                    <h4>{{ now()->format('d F Y') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-card surat-masuk">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-value">150</h2>
                        <p class="card-title">Surat Masuk</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card surat-keluar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-value">89</h2>
                        <p class="card-title">Surat Keluar</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-paper-plane fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card sppd">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-value">45</h2>
                        <p class="card-title">SPPD</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-plane fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card spt">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="card-value">32</h2>
                        <p class="card-title">SPT</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Cards -->
    <div class="row">
        <!-- Surat Masuk -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Surat Masuk</h3>
                <p>Kelola dan pantau surat masuk organisasi</p>
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <!-- Surat Keluar -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <h3>Surat Keluar</h3>
                <p>Kelola dan pantau surat keluar organisasi</p>
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <!-- SPPD Dalam -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>SPPD Dalam Daerah</h3>
                <p>Kelola surat perintah perjalanan dinas dalam daerah</p>
                <a href="{{ route('sppd-dalam-daerah.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <!-- SPPD Luar -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-plane"></i>
                </div>
                <h3>SPPD Luar Daerah</h3>
                <p>Kelola surat perintah perjalanan dinas luar daerah</p>
                <a href="{{ route('sppd-luar-daerah.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <!-- SPT Dalam -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-file-signature"></i>
                </div>
                <h3>SPT Dalam Daerah</h3>
                <p>Kelola surat perintah tugas dalam daerah</p>
                <a href="{{ route('spt-dalam-daerah.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <!-- SPT Luar -->
        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>SPT Luar Daerah</h3>
                <p>Kelola surat perintah tugas luar daerah</p>
                <a href="{{ route('spt-luar-daerah.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>
    </div>
@endsection 