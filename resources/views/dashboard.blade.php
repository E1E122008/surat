@extends('layouts.app')

@section('content')
    <!-- Welcome Section -->
    <div class="dashboard-welcome mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2>Selamat Datang di Sistem Informasi Administrasi Persuratan Biro hukum</h2>
                <p class="text-muted">Pemerintah Sulawesi Tenggara</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="current-date">
                    <h4>{{ now()->format('d F Y') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-5">
        <!-- Grafik Statistik dan Statistik Card dalam satu baris -->
        <div class="col-lg-12 d-flex gap-4">
            <!-- Grafik Statistik -->
            <div class="col-lg-8">
                <div class="card h-100">
                    
                    <div class="card-body">
                        
                        <h5 class="card-title mb-4">Statistik Surat yang diterima</h5>
                        <canvas id="incomingDocumentsChart"></canvas>
                    </div>
                    <div class="card-body">
                    
                        <h5 class="card-title mb-4">Statistik Surat yang dikeluarkan</h5>
                        <canvas id="outgoingDocumentsChart"></canvas>
                    </div>
                </div>
                
            </div>
    
            <!-- Statistik Card -->
            <div class="col-lg-3 mx-2 d-flex flex-column gap-4">
                <div class="dashboard-card surat-masuk p-4">
                    <h2 class="card-value">{{ $jumlahSuratMasuk }}</h2>
                    <p class="card-title">Surat Masuk</p>
                    <i class="fas fa-envelope fa-2x card-icon"></i>
                </div>
                <div class="dashboard-card surat-keluar p-4">
                    <h2 class="card-value">{{ $jumlahSuratKeluar }}</h2>
                    <p class="card-title">Surat Keluar</p>
                    <i class="fas fa-paper-plane fa-2x card-icon"></i>
                </div>
                <div class="dashboard-card draft-phd p-4">
                    <h2 class="card-value">{{ $draftphd }}</h2>
                    <p class="card-title">Registrasi Draft PHD</p>
                    <i class="fas fa-file-alt fa-2x card-icon"></i>
                </div>
                <div class="dashboard-card sppd p-4">
                    <h2 class="card-value">{{ $sppdCount }}</h2>
                    <p class="card-title">SPPD</p>
                    <i class="fas fa-plane fa-2x card-icon"></i>
                </div>
                <div class="dashboard-card spt p-4  ">
                    <h2 class="card-value">{{ $sptCount }}</h2>
                    <p class="card-title">SPT</p>
                    <i class="fas fa-file-alt fa-2x card-icon"></i>
                </div>
            </div> <!-- End Statistik Card -->
        </div> <!-- End Grafik dan Statistik -->
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

        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-gavel"></i>
                </div>
                <h3>Surat Keputusan</h3>
                <p>Kelola dan pantau surat keputusan organisasi</p>
                <a href="{{ route('draft-phd.sk.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3>Peraturan Daerah</h3>
                <p>Kelola dan pantau peraturan daerah</p>
                <a href="{{ route('draft-phd.perda.index') }}" class="btn btn-primary">Lihat Data</a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="dashboard-menu-card">
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3>Peraturan Gubernur</h3>
                <p>Kelola dan pantau peraturan gubernur</p>
                <a href="{{ route('draft-phd.pergub.index') }}" class="btn btn-primary">Lihat Data</a>
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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentIncomingPeriod = 'bulan';
    let currentOutgoingPeriod = 'bulan';
    let incomingChart, outgoingChart;

    async function fetchChartData(period) {
        try {
            const response = await fetch(`/dashboard/chart-data?period=${period}`);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    async function updateCharts(event, chartType) {
        event.preventDefault();
        
        const form = document.getElementById(`form-${chartType}`);
        const period = new FormData(form).get('period');
        
        // Simpan periode yang dipilih
        if (chartType === 'incoming') {
            currentIncomingPeriod = period;
        } else {
            currentOutgoingPeriod = period;
        }

        console.log(`Updating ${chartType} chart for period:`, period);
        
        const data = await fetchChartData(period);
        if (!data) return;

        if (chartType === 'incoming' && incomingChart) {
            incomingChart.data.labels = data.labels;
            incomingChart.data.datasets[0].data = data.suratMasukData;
            incomingChart.data.datasets[1].data = data.skData;
            incomingChart.data.datasets[2].data = data.perdaData;
            incomingChart.data.datasets[3].data = data.pergubData;
            incomingChart.update();
        }

        if (chartType === 'outgoing' && outgoingChart) {
            outgoingChart.data.labels = data.labels;
            outgoingChart.data.datasets[0].data = data.suratKeluarData;
            outgoingChart.data.datasets[1].data = data.sppdDalamData;
            outgoingChart.data.datasets[2].data = data.sppdLuarData;
            outgoingChart.data.datasets[3].data = data.sptDalamData;
            outgoingChart.data.datasets[4].data = data.sptLuarData;
            outgoingChart.update();
        }

        // Set kembali nilai dropdown sesuai periode yang dipilih
        document.getElementById(`filter-waktu-${chartType === 'incoming' ? 'masuk' : 'keluar'}`).value = period;
    }

    // Inisialisasi Chart Surat Masuk
    const ctxIncoming = document.getElementById('incomingDocumentsChart').getContext('2d');
    incomingChart = new Chart(ctxIncoming, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Surat Masuk',
                data: @json($suratMasukData),
                borderColor: '#4C1D95',
                tension: 0.1
            }, {
                label: 'Surat Keputusan',
                data: @json($skData),
                borderColor: '#713F12',
                tension: 0.1
            }, {
                label: 'Perda',
                data: @json($perdaData),
                borderColor: '#FEF08A',
                tension: 0.1
            }, {
                label: 'Pergub',
                data: @json($pergubData),
                borderColor: 'yellow',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Dokumen Surat Masuk per Bulan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Inisialisasi Chart Surat Keluar
    const ctxOutgoing = document.getElementById('outgoingDocumentsChart').getContext('2d');
    outgoingChart = new Chart(ctxOutgoing, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Surat Keluar',
                data: @json($suratKeluarData),
                borderColor: 'green',
                tension: 0.1
            }, {
                label: 'SPPD DD',
                data: @json($sppdDalamData),
                borderColor: 'blue)',
                tension: 0.1
            }, {
                label: 'SPPD LD',
                data: @json($sppdLuarData),
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }, {
                label: 'SPT DD',
                data: @json($sptDalamData),
                borderColor: 'orange',
                tension: 0.1
            }, {
                label: 'SPT LD',
                data: @json($sptLuarData),
                borderColor: 'rgb(54, 162, 235)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Dokumen Surat yang dikeluarkan per Bulan'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Load data awal
    updateCharts({ preventDefault: () => {} }, 'incoming');
    updateCharts({ preventDefault: () => {} }, 'outgoing');
});
</script>

@push('scripts')
@endpush 