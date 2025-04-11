@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h2 class="mb-4"><strong>📋 Transaksi</strong> / <span style="color: gray;">Transaksi Surat</span></h2>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end gap-2">
                    <form action="{{ route('transaksi-surat.index') }}" method="GET" class="d-flex me-2" style="width: 300px;">
                        <input type="text" 
                               name="search" 
                               class="form-control me-2" 
                               placeholder="Cari surat..."
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <input type="hidden" name="tab" value="{{ request('tab', 'surat-masuk') }}">
                    </form>
                    <button type="button" class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#requestModal">
                        <i class="fas fa-paper-plane me-2"></i>Minta Persetujuan
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-x-auto w-full shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab', 'surat-masuk') == 'surat-masuk' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'surat-masuk']) }}">
                                Surat Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'surat-keluar' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'surat-keluar']) }}">
                                Surat Keluar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sk' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'sk']) }}">
                                SK
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'perda' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'perda']) }}">
                                PERDA
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'pergub' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'pergub']) }}">
                                PERGUB
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-dalam' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'sppd-dalam']) }}">
                                SPPD DD
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'sppd-luar' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'sppd-luar']) }}">
                                SPPD LD
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-dalam' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'spt-dalam']) }}">
                                SPT DD
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request('tab') == 'spt-luar' ? 'active' : '' }}"
                               href="{{ route('transaksi-surat.index', ['tab' => 'spt-luar']) }}">
                                SPT LD
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content mt-3">
                    <!-- Tab Surat Masuk -->
                    @include('transaksi-surat.partials.surat-masuk')

                    <!-- Tab Surat Keluar -->
                    @include('transaksi-surat.partials.surat-keluar')

                    <!-- Tab SK -->
                    @include('transaksi-surat.partials.sk')

                    <!-- Tab PERDA -->
                    @include('transaksi-surat.partials.perda')

                    <!-- Tab PERGUB -->
                    @include('transaksi-surat.partials.pergub')
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Request Approval -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Permintaan Persetujuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('transaksi-surat.request-approval') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="letter_type" class="form-label">Jenis Surat</label>
                            <select class="form-select" id="letter_type" name="letter_type" required>
                                <option value="">Pilih Jenis Surat</option>
                                <option value="surat_masuk">Surat Masuk</option>
                                <option value="surat_keluar">Surat Keluar</option>
                                <option value="sk">SK</option>
                                <option value="perda">PERDA</option>
                                <option value="pergub">PERGUB</option>
                                <option value="sppd_dalam">SPPD Dalam Daerah</option>
                                <option value="sppd_luar">SPPD Luar Daerah</option>
                                <option value="spt_dalam">SPT Dalam Daerah</option>
                                <option value="spt_luar">SPT Luar Daerah</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sender" class="form-label">Pengirim</label>
                            <input type="text" class="form-control" id="sender" name="sender" 
                                placeholder="Masukkan nama pengirim/instansi pengirim" required>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                placeholder="Jelaskan alasan permintaan persetujuan..." required></textarea>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Permintaan persetujuan akan diproses oleh admin. Anda akan mendapatkan notifikasi setelah permintaan disetujui atau ditolak.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i>Kirim Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection