@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-chart-line me-1"></i> Dashboard Utama</span>
@endsection

@section('content')
    <!-- Welcome Section -->
    <!-- Welcome Section -->
    @if (session('error'))
        <div id="auto-dismiss-alert"
            class="alert alert-danger alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4"
            role="alert"
            style="background-color: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444 !important; border-radius: 8px;">
            <i class="fas fa-exclamation-triangle me-3 fs-5" style="color: #ef4444;"></i>
            <div>
                <strong class="d-block mb-1">Akses Ditolak</strong>
                <span style="font-size: 14.5px;">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"
                style="filter: invert(34%) sepia(85%) saturate(1915%) hue-rotate(338deg) brightness(97%) contrast(98%);"></button>
        </div>
    @endif
    @if (session('success'))
        <div id="auto-dismiss-alert-success"
            class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4"
            role="alert"
            style="background-color: #dcfce7; color: #166534; border-left: 4px solid #22c55e !important; border-radius: 8px;">
            <i class="fas fa-check-circle me-3 fs-5" style="color: #22c55e;"></i>
            <div>
                <span style="font-size: 14.5px;">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="dashboard-welcome p-4 rounded-4 shadow-sm"
        style="background-color: var(--putih-kartu); border: 1px solid var(--border-sangat-tipis);">
        <div class="row">
            <!-- Header Section -->
            <div class="col-12 pb-3 mb-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h3 class="mb-0" style="color: var(--navy-utama); font-size: 20px; font-weight: 700;">
                            {!! nl2br(
                                e(
                                    explode(
                                        "\n",
                                        $systemSetting->headline ??
                                            "Selamat Datang di Sistem Informasi Administrasi Persuratan Biro Hukum\nSekretariat Daerah Provinsi Sulawesi Tenggara",
                                    )[0] ?? '',
                                ),
                            ) !!}
                            <span class="d-block mt-2" style="color: var(--slate-500); font-size: 14px; font-weight: 500;">
                                {!! nl2br(
                                    e(
                                        implode(
                                            "\n",
                                            array_slice(
                                                explode(
                                                    "\n",
                                                    $systemSetting->headline ??
                                                        "Selamat Datang di Sistem Informasi Administrasi Persuratan Biro Hukum\nSekretariat Daerah Provinsi Sulawesi Tenggara",
                                                ),
                                                1,
                                            ),
                                        ),
                                    ),
                                ) !!}
                            </span>
                        </h3>
                    </div>
                    <div>
                        <span
                            style="color: var(--slate-400); font-size: 13px; font-weight: 400;">{{ now()->format('d F Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-12">
                <!-- Description Block -->
                <div class="description-block p-4 rounded-4" style="background-color: var(--bg-kotak);">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-balance-scale me-3 mt-1" style="color: var(--slate-500); font-size: 18px;"></i>
                        <p class="mb-0"
                            style="color: var(--slate-700); font-size: 13.5px; font-weight: 400; line-height: 1.7;">
                            {{ $systemSetting->description ?? 'Biro Hukum mempunyai tugas membantu asisten pemerintahan dan kesejahteraan rakyat dalam penyiapan perumusan kebijakan daerah, pengoordinasian pelaksanaan tugas perangkat daerah, pemantauan dan evaluasi pelaksanaan kebijakan di bidang peraturan perundang-undangan provinsi, peraturan perundang-undangan kabupaten/kota, dan bantuan hukum.' }}
                        </p>
                    </div>
                </div>

                <!-- Jam Layanan Operasional -->
                <div class="mt-3 p-3 rounded-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center"
                    style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="d-flex align-items-center mb-2 mb-md-0" style="color: #3b82f6;">
                        <i class="far fa-clock me-2"></i>
                        <span
                            style="font-size: 13px; font-weight: 500;">{{ $systemSetting->operational_time ?? '[Jam layanan operasional — Senin-Jumat, 08.00-16.00 WITA]' }}</span>
                    </div>
                    <div style="font-size: 12.5px; color: #64748b;">
                        Diluar jam layanan, pesan tetap tersimpan dan akan direspons pada hari kerja berikutnya.
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
        @if (in_array(Auth::user()->role, ['admin', 'monitor', 'superadmin']))
            <!-- Charts Section -->
            <div class="row mb-5 mt-4">
                <!-- Chart: Surat Masuk -->
                <div class="mb-4">
                    <div class="card bg-white shadow-sm border-0">
                        <div
                            class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0">
                            <div>
                                <h5 class="mb-0" style="font-weight: 700; color: #0f1b3d;">
                                    <i class="fas fa-arrow-down-long me-2" style="color: #3b82f6;"></i>Dokumen Surat Masuk
                                </h5>
                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">Tren penerimaan dokumen berdasarkan
                                    periode</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted d-inline-flex align-items-center"
                                    style="font-size: 13px; font-weight: 500;"><i class="far fa-clock me-1"></i>Periode:</span>
                                <select id="filter-waktu-masuk" class="form-select form-select-sm"
                                    onchange="updateCharts(event, 'incoming')"
                                    style="border-radius: 20px; font-size: 13px; font-weight: 600; padding: 5px 14px; border: 1.5px solid #e2e8f0; background-color: #f8fafc; min-width: 155px; cursor: pointer;">
                                    <option value="minggu">📅 7 Minggu Terakhir</option>
                                    <option value="bulan" selected>📅 6 Bulan Terakhir</option>
                                    <option value="tahun">📅 12 Bulan Terakhir</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 320px; width: 100%;">
                                <canvas id="incomingDocumentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart: Surat Keluar -->
                <div class="mb-4">
                    <div class="card bg-white shadow-sm border-0">
                        <div
                            class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0">
                            <div>
                                <h5 class="mb-0" style="font-weight: 700; color: #0f1b3d;">
                                    <i class="fas fa-arrow-up-long me-2" style="color: #0d9488;"></i>Dokumen Surat Keluar
                                </h5>
                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">Tren pengiriman dokumen berdasarkan
                                    periode</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted d-inline-flex align-items-center"
                                    style="font-size: 13px; font-weight: 500;"><i class="far fa-clock me-1"></i>Periode:</span>
                                <select id="filter-waktu-keluar" class="form-select form-select-sm"
                                    onchange="updateCharts(event, 'outgoing')"
                                    style="border-radius: 20px; font-size: 13px; font-weight: 600; padding: 5px 14px; border: 1.5px solid #e2e8f0; background-color: #f8fafc; min-width: 155px; cursor: pointer;">
                                    <option value="minggu">📅 7 Minggu Terakhir</option>
                                    <option value="bulan" selected>📅 6 Bulan Terakhir</option>
                                    <option value="tahun">📅 12 Bulan Terakhir</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="position: relative; height: 320px; width: 100%;">
                                <canvas id="outgoingDocumentsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-5 g-4 pb-2">
                    <div class="col">
                        <div class="dashboard-card surat-masuk p-4 bg-white shadow-sm">
                            <h2 class="card-value">{{ $jumlahSuratMasuk }}</h2>
                            <p class="card-title">Surat Masuk</p>
                            <i class="fas fa-envelope fa-2x card-icon"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="dashboard-card surat-keluar p-4 bg-white shadow-sm">
                            <h2 class="card-value">{{ $jumlahSuratKeluar }}</h2>
                            <p class="card-title">Surat Keluar</p>
                            <i class="fas fa-paper-plane fa-2x card-icon"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="dashboard-card draft-phd p-4 bg-white shadow-sm">
                            <h2 class="card-value">{{ $draftphd }}</h2>
                            <p class="card-title">Registrasi Draft PHD</p>
                            <i class="fas fa-file-alt fa-2x card-icon"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="dashboard-card spt p-4 bg-white shadow-sm">
                            <h2 class="card-value">{{ $sptCount }}</h2>
                            <p class="card-title">Surat Perintah Tugas</p>
                            <i class="fas fa-file-signature fa-2x card-icon"></i>
                        </div>
                    </div>
                    <div class="col">
                        <div class="dashboard-card sppd p-4 bg-white shadow-sm">
                            <h2 class="card-value">{{ $sppdCount }}</h2>
                            <p class="card-title">Surat Perintah Perjalanan Dinas</p>
                            <i class="fas fa-plane fa-2x card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth




    <!-- Menu Cards -->
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentIncomingPeriod = 'bulan';
            let currentOutgoingPeriod = 'bulan';
            let incomingChart, outgoingChart;

            // Fungsi untuk menghitung stepSize dinamis berdasarkan nilai maksimum
            function calculateStepSize(maxValue) {
                if (maxValue === 0) return 1;
                if (maxValue <= 10) return 1;
                if (maxValue <= 50) return 5;
                if (maxValue <= 100) return 10;
                if (maxValue <= 200) return 20;
                if (maxValue <= 500) return 50;
                return Math.ceil(maxValue / 10);
            }

            // Fungsi untuk mendapatkan nilai maksimum dari semua dataset
            function getMaxValue(datasets) {
                let max = 0;
                datasets.forEach(dataset => {
                    if (Array.isArray(dataset.data)) {
                        const datasetMax = Math.max(...dataset.data);
                        if (datasetMax > max) max = datasetMax;
                    }
                });
                return max;
            }

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
                if (event && event.preventDefault) {
                    event.preventDefault();
                }

                const selectEl = document.getElementById(
                    chartType === 'incoming' ? 'filter-waktu-masuk' : 'filter-waktu-keluar'
                );
                const period = selectEl ? selectEl.value : 'bulan';

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

                    // Update skala Y-axis secara dinamis
                    const maxValue = getMaxValue(incomingChart.data.datasets);
                    const stepSize = calculateStepSize(maxValue);
                    incomingChart.options.scales.y.ticks.stepSize = stepSize;
                    incomingChart.options.scales.y.max = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;

                    incomingChart.update();
                }

                if (chartType === 'outgoing' && outgoingChart) {
                    outgoingChart.data.labels = data.labels;
                    outgoingChart.data.datasets[0].data = data.suratKeluarData;
                    outgoingChart.data.datasets[1].data = data.sppdDalamData;
                    outgoingChart.data.datasets[2].data = data.sppdLuarData;
                    outgoingChart.data.datasets[3].data = data.sptDalamData;
                    outgoingChart.data.datasets[4].data = data.sptLuarData;

                    // Update skala Y-axis secara dinamis
                    const maxValue = getMaxValue(outgoingChart.data.datasets);
                    const stepSize = calculateStepSize(maxValue);
                    outgoingChart.options.scales.y.ticks.stepSize = stepSize;
                    outgoingChart.options.scales.y.max = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;

                    outgoingChart.update();
                }

                // Set kembali nilai dropdown sesuai periode yang dipilih
                const filterElement = document.getElementById(
                    `filter-waktu-${chartType === 'incoming' ? 'masuk' : 'keluar'}`);
                if (filterElement) {
                    filterElement.value = period;
                }
            }

            // Inisialisasi Chart Surat Masuk
            const incomingChartElement = document.getElementById('incomingDocumentsChart');
            if (!incomingChartElement) {
                console.log('Chart element not found, skipping chart initialization');
            } else {
                const ctxIncoming = incomingChartElement.getContext('2d');
                // Hitung stepSize dinamis untuk grafik incoming
                const incomingDatasets = [{
                        data: @json($suratMasukData)
                    },
                    {
                        data: @json($skData)
                    },
                    {
                        data: @json($perdaData)
                    },
                    {
                        data: @json($pergubData)
                    }
                ];
                const incomingMaxValue = Math.max(
                    ...incomingDatasets.flatMap(d => d.data || [0])
                );
                const incomingStepSize = incomingMaxValue <= 10 ? 1 :
                    incomingMaxValue <= 50 ? 5 :
                    incomingMaxValue <= 100 ? 10 :
                    Math.ceil(incomingMaxValue / 10);

                incomingChart = new Chart(ctxIncoming, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Surat Masuk',
                            data: @json($suratMasukData),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'Surat Keputusan',
                            data: @json($skData),
                            borderColor: '#0d9488',
                            backgroundColor: 'rgba(13, 148, 136, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#0d9488',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'Perda',
                            data: @json($perdaData),
                            borderColor: '#d97706',
                            backgroundColor: 'rgba(217, 119, 6, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#d97706',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'Pergub',
                            data: @json($pergubData),
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#7c3aed',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                display: true,
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 20,
                                    color: '#64748b',
                                    font: {
                                        family: "'Plus Jakarta Sans', sans-serif",
                                        size: 11.5,
                                        weight: 400
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Dokumen Surat Masuk per Bulan',
                                color: '#1e293b',
                                font: {
                                    family: "'Poppins', sans-serif",
                                    size: 14,
                                    weight: 700
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: incomingStepSize,
                                    precision: 0
                                },
                                max: incomingMaxValue > 0 ? Math.ceil(incomingMaxValue * 1.1) : 10
                            },
                            x: {
                                display: true,
                                grid: {
                                    display: false
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }

            // Inisialisasi Chart Surat Keluar
            const outgoingChartElement = document.getElementById('outgoingDocumentsChart');
            if (!outgoingChartElement) {
                console.log('Chart element not found, skipping chart initialization');
            } else {
                const ctxOutgoing = outgoingChartElement.getContext('2d');
                // Hitung stepSize dinamis untuk grafik outgoing
                const outgoingDatasets = [{
                        data: @json($suratKeluarData)
                    },
                    {
                        data: @json($sppdDalamData)
                    },
                    {
                        data: @json($sppdLuarData)
                    },
                    {
                        data: @json($sptDalamData)
                    },
                    {
                        data: @json($sptLuarData)
                    }
                ];
                const outgoingMaxValue = Math.max(
                    ...outgoingDatasets.flatMap(d => d.data || [0])
                );
                const outgoingStepSize = outgoingMaxValue <= 10 ? 1 :
                    outgoingMaxValue <= 50 ? 5 :
                    outgoingMaxValue <= 100 ? 10 :
                    Math.ceil(outgoingMaxValue / 10);

                outgoingChart = new Chart(ctxOutgoing, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [{
                            label: 'Surat Keluar',
                            data: @json($suratKeluarData),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'SPPD DD',
                            data: @json($sppdDalamData),
                            borderColor: '#0d9488',
                            backgroundColor: 'rgba(13, 148, 136, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#0d9488',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'SPPD LD',
                            data: @json($sppdLuarData),
                            borderColor: '#d97706',
                            backgroundColor: 'rgba(217, 119, 6, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#d97706',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'SPT DD',
                            data: @json($sptDalamData),
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#7c3aed',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }, {
                            label: 'SPT LD',
                            data: @json($sptLuarData),
                            borderColor: '#64748b',
                            backgroundColor: 'rgba(100, 116, 139, 0.1)',
                            fill: false,
                            tension: 0.1,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#64748b',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                display: true,
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    padding: 20,
                                    color: '#64748b',
                                    font: {
                                        family: "'Plus Jakarta Sans', sans-serif",
                                        size: 11.5,
                                        weight: 400
                                    }
                                }
                            },
                            title: {
                                display: true,
                                text: 'Dokumen Surat yang Dikeluarkan per Bulan',
                                color: '#1e293b',
                                font: {
                                    family: "'Poppins', sans-serif",
                                    size: 14,
                                    weight: 700
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: outgoingStepSize,
                                    precision: 0
                                },
                                max: outgoingMaxValue > 0 ? Math.ceil(outgoingMaxValue * 1.1) : 10
                            },
                            x: {
                                display: true,
                                grid: {
                                    display: false
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });
            }

            // Load data awal (hanya jika chart elements ada)
            if (incomingChartElement && outgoingChartElement) {
                updateCharts({
                    preventDefault: () => {}
                }, 'incoming');
                updateCharts({
                    preventDefault: () => {}
                }, 'outgoing');
            }
        });
    </script>


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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let errorAlert = document.getElementById('auto-dismiss-alert');
                let successAlert = document.getElementById('auto-dismiss-alert-success');

                if (errorAlert) {
                    let bsAlert = new bootstrap.Alert(errorAlert);
                    bsAlert.close();
                }

                if (successAlert) {
                    let bsAlert = new bootstrap.Alert(successAlert);
                    bsAlert.close();
                }
            }, 5000);
        });
    </script>
@endpush
