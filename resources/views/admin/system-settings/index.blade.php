@extends('layouts.app')

@section('breadcrumb')
    <i class="fas fa-chevron-right separator"></i> <span style="color: white; font-weight: 600;"><i
            class="fas fa-cogs me-1"></i> Pengaturan Sistem</span>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center mb-4"
            role="alert"
            style="background-color: #dcfce7; color: #166534; border-left: 4px solid #22c55e !important; border-radius: 8px;">
            <i class="fas fa-check-circle me-3 fs-5" style="color: #22c55e;"></i>
            <div>
                <span style="font-size: 14.5px;">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
        <!-- Nav Tabs -->
        <ul class="nav nav-pills mb-4 pb-2" id="settingsTab" role="tablist" style="border-bottom: 1px solid #e2e8f0;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="view-tab" data-bs-toggle="tab" data-bs-target="#view-content"
                    type="button" role="tab" aria-controls="view-content" aria-selected="true"
                    style="font-weight: 600; border-radius: 8px;">
                    <i class="fas fa-paint-brush me-2"></i> Tampilan & Teks
                </button>
            </li>
            <li class="nav-item ms-2" role="presentation">
                <button class="nav-link" id="chart-tab" data-bs-toggle="tab" data-bs-target="#chart-content" type="button"
                    role="tab" aria-controls="chart-content" aria-selected="false"
                    style="font-weight: 600; border-radius: 8px;">
                    <i class="fas fa-chart-bar me-2"></i> Grafik & Laporan
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="settingsTabContent">

            <!-- Tab 1: Form Teks -->
            <div class="tab-pane fade show active" id="view-content" role="tabpanel" aria-labelledby="view-tab">
                <form action="{{ route('system-settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Headline -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Teks Headline Utama (Selamat
                                Datang)</label>
                            <textarea name="headline" class="form-control" rows="2" required
                                placeholder="Contoh: Selamat Datang di...&#10;Sekretariat Daerah...">{{ old('headline', $setting->headline) }}</textarea>
                            @error('headline')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Teks Deskripsi (Tugas Biro
                                Hukum)</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description', $setting->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Operational Time -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Keterangan Jam Layanan
                                Operasional</label>
                            <input type="text" name="operational_time" class="form-control" required
                                value="{{ old('operational_time', $setting->operational_time) }}">
                            @error('operational_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Logo Upload (Opsional) -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label" style="font-weight: 600; color: #334155;">Logo Beranda (Biarkan kosong
                                jika tidak ingin mengubah)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            @if ($setting->logo)
                                <div class="mt-2">
                                    <small class="text-muted">Logo saat ini tesimpan.</small>
                                </div>
                            @endif
                            @error('logo')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary px-4 py-2"
                                style="font-weight: 600; border-radius: 8px;">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tab 2: Grafik & Laporan -->
            <div class="tab-pane fade" id="chart-content" role="tabpanel" aria-labelledby="chart-tab">
                <div class="d-flex justify-content-between align-items-center mb-4 printable-hide">
                    <div>
                        <h4 class="mb-1" style="font-weight: 700; color: #1e293b;">Analitik Surat</h4>
                        <p class="text-muted mb-0" style="font-size: 14px;">Pantau statistik surat berdasarkan periode
                            tertentu.</p>
                    </div>
                    <button class="btn btn-outline-secondary" onclick="exportPDF()"
                        style="font-weight: 600; border-radius: 8px;">
                        <i class="fas fa-file-pdf me-2"></i> Ekspor PDF
                    </button>
                </div>

                <div class="printable-section">
                    <!-- Incoming Documents -->
                    <div class="card bg-white shadow-sm mb-4 border-0">
                        <div
                            class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0 printable-hide">
                            <div>
                                <h5 class="mb-0" style="font-weight: 700; color: #0f1b3d;"><i
                                        class="fas fa-arrow-down-long me-2" style="color: #3b82f6;"></i>Dokumen Surat
                                    Masuk</h5>
                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">Tren penerimaan dokumen
                                    berdasarkan periode</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted d-inline-flex align-items-center"
                                    style="font-size: 13px; font-weight: 500;"><i
                                        class="far fa-clock me-1"></i>Periode:</span>
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
                            <div style="position: relative; height: 350px; width: 100%;">
                                <canvas id="incomingChartSystem"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Outgoing Documents -->
                    <div class="card bg-white shadow-sm mb-4 border-0">
                        <div
                            class="card-header bg-transparent border-0 d-flex justify-content-between align-items-center pt-4 pb-0 printable-hide">
                            <div>
                                <h5 class="mb-0" style="font-weight: 700; color: #0f1b3d;"><i
                                        class="fas fa-arrow-up-long me-2" style="color: #0d9488;"></i>Dokumen Surat Keluar
                                </h5>
                                <p class="text-muted mb-0 mt-1" style="font-size: 12.5px;">Tren pengiriman dokumen
                                    berdasarkan periode</p>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-muted d-inline-flex align-items-center"
                                    style="font-size: 13px; font-weight: 500;"><i
                                        class="far fa-clock me-1"></i>Periode:</span>
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
                            <div style="position: relative; height: 350px; width: 100%;">
                                <canvas id="outgoingChartSystem"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function exportPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            doc.setFontSize(14);
            doc.text('LAPORAN ANALITIK SURAT MASUK / KELUAR', 105, 15, {
                align: 'center'
            });
            doc.line(15, 20, 195, 20);

            doc.setFontSize(11);
            doc.text('GRAFIK SURAT MASUK', 15, 30);
            if (incomingChartSys) {
                const incomingImg = incomingChartSys.toBase64Image('image/png', 1.0);
                doc.addImage(incomingImg, 'PNG', 15, 35, 180, 80);

                let sums = incomingChartSys.data.datasets.map(d => d.data.reduce((a, b) => a + b, 0));
                doc.setFontSize(9);
                doc.setTextColor(100);
                doc.text(
                    `Total Surat Masuk: ${sums[0]} | Surat Keputusan: ${sums[1]} | Perda: ${sums[2]} | Pergub: ${sums[3]}`,
                    15, 120);
            }

            doc.setTextColor(0);
            doc.setFontSize(11);
            doc.text('GRAFIK SURAT KELUAR', 15, 132);
            if (outgoingChartSys) {
                const outgoingImg = outgoingChartSys.toBase64Image('image/png', 1.0);
                doc.addImage(outgoingImg, 'PNG', 15, 137, 180, 80);

                let sums = outgoingChartSys.data.datasets.map(d => d.data.reduce((a, b) => a + b, 0));
                doc.setFontSize(9);
                doc.setTextColor(100);
                doc.text(
                    `Total Surat Keluar: ${sums[0]} | SPPD DD: ${sums[1]} | SPPD LD: ${sums[2]} | SPT DD: ${sums[3]} | SPT LD: ${sums[4]}`,
                    15, 222);
            }

            doc.save('Laporan-Analitik-Surat.pdf');
        }

        let incomingChartSys, outgoingChartSys;

        function calculateStepSizeSys(maxValue) {
            if (maxValue === 0) return 1;
            if (maxValue <= 10) return 1;
            if (maxValue <= 50) return 5;
            if (maxValue <= 100) return 10;
            if (maxValue <= 200) return 20;
            if (maxValue <= 500) return 50;
            return Math.ceil(maxValue / 10);
        }

        function getMaxValueSys(datasets) {
            let max = 0;
            datasets.forEach(dataset => {
                if (Array.isArray(dataset.data)) {
                    const datasetMax = Math.max(...dataset.data);
                    if (datasetMax > max) max = datasetMax;
                }
            });
            return max;
        }

        async function fetchChartDataSys(period) {
            try {
                const response = await fetch(`/dashboard/chart-data?period=${period}`);
                if (!response.ok) throw new Error('Network response was not ok');
                return await response.json();
            } catch (error) {
                console.error('Error fetching data:', error);
                return null;
            }
        }

        async function updateCharts(event, chartType) {
            if (event && event.preventDefault) event.preventDefault();

            let period = 'bulan';
            if (chartType === 'incoming') {
                period = document.getElementById('filter-waktu-masuk').value;
            } else {
                period = document.getElementById('filter-waktu-keluar').value;
            }

            const data = await fetchChartDataSys(period);
            if (!data) return;

            if (chartType === 'incoming') {
                if (!incomingChartSys) {
                    initChartSys('incoming', data);
                } else {
                    incomingChartSys.data.labels = data.labels;
                    incomingChartSys.data.datasets[0].data = data.suratMasukData;
                    incomingChartSys.data.datasets[1].data = data.skData || [];
                    incomingChartSys.data.datasets[2].data = data.perdaData || [];
                    incomingChartSys.data.datasets[3].data = data.pergubData || [];

                    const maxValue = getMaxValueSys(incomingChartSys.data.datasets);
                    incomingChartSys.options.scales.y.ticks.stepSize = calculateStepSizeSys(maxValue);
                    incomingChartSys.options.scales.y.max = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;
                    incomingChartSys.update();
                }
            }

            if (chartType === 'outgoing') {
                if (!outgoingChartSys) {
                    initChartSys('outgoing', data);
                } else {
                    outgoingChartSys.data.labels = data.labels;
                    outgoingChartSys.data.datasets[0].data = data.suratKeluarData;
                    outgoingChartSys.data.datasets[1].data = data.sppdDalamData;
                    outgoingChartSys.data.datasets[2].data = data.sppdLuarData;
                    outgoingChartSys.data.datasets[3].data = data.sptDalamData;
                    outgoingChartSys.data.datasets[4].data = data.sptLuarData;

                    const maxValue = getMaxValueSys(outgoingChartSys.data.datasets);
                    outgoingChartSys.options.scales.y.ticks.stepSize = calculateStepSizeSys(maxValue);
                    outgoingChartSys.options.scales.y.max = maxValue > 0 ? Math.ceil(maxValue * 1.1) : 10;
                    outgoingChartSys.update();
                }
            }
        }

        function initChartSys(type, initialData) {
            if (type === 'incoming') {
                const ctx = document.getElementById('incomingChartSystem').getContext('2d');
                incomingChartSys = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: initialData.labels,
                        datasets: [{
                                label: 'Surat Masuk',
                                data: initialData.suratMasukData,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'Surat Keputusan',
                                data: initialData.skData || [],
                                borderColor: '#0d9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'Perda',
                                data: initialData.perdaData || [],
                                borderColor: '#d97706',
                                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'Pergub',
                                data: initialData.pergubData || [],
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                fill: false,
                                tension: 0.1
                            }
                        ]
                    },
                    options: getChartOptionsSys('Dokumen Surat Masuk')
                });
            } else {
                const ctx = document.getElementById('outgoingChartSystem').getContext('2d');
                outgoingChartSys = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: initialData.labels,
                        datasets: [{
                                label: 'Surat Keluar',
                                data: initialData.suratKeluarData,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'SPPD DD',
                                data: initialData.sppdDalamData,
                                borderColor: '#0d9488',
                                backgroundColor: 'rgba(13, 148, 136, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'SPPD LD',
                                data: initialData.sppdLuarData,
                                borderColor: '#d97706',
                                backgroundColor: 'rgba(217, 119, 6, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'SPT DD',
                                data: initialData.sptDalamData,
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                fill: false,
                                tension: 0.1
                            },
                            {
                                label: 'SPT LD',
                                data: initialData.sptLuarData,
                                borderColor: '#64748b',
                                fill: false,
                                tension: 0.1
                            }
                        ]
                    },
                    options: getChartOptionsSys('Dokumen Surat Keluar')
                });
            }
        }

        function getChartOptionsSys(titleText) {
            return {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    title: {
                        display: false
                    } // We use card header instead
                }
            };
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Tab shown event to trigger chart render specifically for Tab 2
            var chartTabSelected = document.getElementById('chart-tab')
            chartTabSelected.addEventListener('shown.bs.tab', function(event) {
                updateCharts(null, 'incoming');
                updateCharts(null, 'outgoing');
            })
        });
    </script>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .printable-hide {
                display: none !important;
            }

            .printable-section,
            .printable-section * {
                visibility: visible;
            }

            .printable-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
