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
    
@endsection