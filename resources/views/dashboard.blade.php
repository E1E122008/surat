@extends('layouts.app')

@section('content')
    <!-- Welcome Section -->
    <div class="dashboard-welcome p-4 bg-white rounded-lg shadow-sm">
        <div class="row">
            <!-- Header Section -->
            <div class="col-12 border-bottom pb-4 mb-4">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="h4 fw-bold text-primary mb-0">
                            Selamat Datang di Sistem Informasi Administrasi Persuratan Biro Hukum
                            <span class="d-block small text-secondary mt-1">Pemerintah Sulawesi Tenggara</span>
                        </h3>
                    </div>
                    <div class="text-secondary">
                        <span class="small">{{ now()->format('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-12">
                <!-- Description Block -->
                <div class="description-block mb-5 p-4 bg-light rounded-3">
                    <div class="d-flex">
                        <i class="fas fa-balance-scale fs-4 text-primary me-3 mt-1"></i>
                        <p class="text-muted mb-0 fs-6 lh-base">
                            Biro hukum mempunyai tugas membantu asisten pemerintahan dan kesejahteraan rakyat dalam penyiapan perumusan kebijakan daerah, pengoordinasian pelaksanaan tugas perangkat daerah, pemantauan dan evaluasi pelaksanaan kebijakan di bidang peraturan perundang-undangan provinsi, peraturan perundang-undangan kabupaten/kota dan bantuan hukum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    @if(Auth::user()->role === 'admin')
    <!-- Charts Section -->
    <div class="row mb-5 mt-4">
        <!-- Grafik Statistik -->
        <div class="mb-4">
            <div class="card h-100 bg-white shadow-sm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Statistik Surat yang diterima</h5>
                            <canvas id="incomingDocumentsChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h5 class="card-title mb-4">Statistik Surat yang dikeluarkan</h5>
                            <canvas id="outgoingDocumentsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistik Card -->
        <div class="d-flex gap-4 overflow-x-auto pb-2">
            <div class="dashboard-card surat-masuk p-4 bg-white shadow-sm" style="min-width: 240px;">
                <h2 class="card-value">{{ $jumlahSuratMasuk }}</h2>
                <p class="card-title">Surat Masuk</p>
                <i class="fas fa-envelope fa-2x card-icon"></i>
            </div>
            <div class="dashboard-card surat-keluar p-4 bg-white shadow-sm" style="min-width: 240px;">
                <h2 class="card-value">{{ $jumlahSuratKeluar }}</h2>
                <p class="card-title">Surat Keluar</p>
                <i class="fas fa-paper-plane fa-2x card-icon"></i>
            </div>
            <div class="dashboard-card draft-phd p-4 bg-white shadow-sm" style="min-width: 240px;">
                <h2 class="card-value">{{ $draftphd }}</h2>
                <p class="card-title">Registrasi Draft PHD</p>
                <i class="fas fa-file-alt fa-2x card-icon"></i>
            </div>
            <div class="dashboard-card spt p-4 bg-white shadow-sm" style="min-width: 240px;">
                <h2 class="card-value">{{ $sptCount }}</h2>
                <p class="card-title">Surat Perintah Tugas</p>
                <i class="fas fa-file-signature fa-2x card-icon"></i>
            </div>
            <div class="dashboard-card sppd p-4 bg-white shadow-sm" style="min-width: 240px;">
                <h2 class="card-value">{{ $sppdCount }}</h2>
                <p class="card-title">Surat Perintah Perjalanan Dinas</p>
                <i class="fas fa-plane fa-2x card-icon"></i>
            </div>
        </div>
    </div>
    @endif
    @endauth
    
    


    <!-- Menu Cards -->
    
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

<style>
.dashboard-welcome {
    background: linear-gradient(to right bottom, #ffffff, #f8f9fa);
}

.date-badge {
    min-width: 120px;
    border-left: 4px solid var(--bs-primary);
}

.function-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(var(--bs-primary-rgb), 0.1);
    border-radius: 50%;
}

.list-group-item {
    transition: transform 0.2s ease;
}

.list-group-item:hover {
    transform: translateX(10px);
}

.dashboard-card {
    flex: 1;
    min-width: 240px;
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-value {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #ffffff;
}

.card-title {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-icon {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #ffffff;
    opacity: 0.2;
}

.bg-gray-100 {
    background-color: #f3f4f6 !important;
}

.shadow-sm {
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
}

.card {
    border: none !important;
    border-radius: 10px;
    overflow: hidden;
}

.card-body {
    padding: 1.5rem;
}

/* Add styles for horizontal scrolling */
.overflow-x-auto {
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 3px;
}
</style> 