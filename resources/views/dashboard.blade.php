@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1>Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-12">
            @if (auth()->user()->role->role === 'kepala gudang')
                @if ($orders->count() == 0)
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Saat ini belum ada permintaan barang
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Saat ini terdapat {{ $orders->count() }} permintaan barang menunggu konfirmasi. 
                        <a href="{{ route('permintaan-produk.index') }}" class="ml-1" style="color: #0000e6; text-decoration: underline;">Lihat Detail Permintaan</a>
                    </div>
                @endif
            @elseif (auth()->user()->role->role === 'admin gudang')
                @if ($ordersAdmin->count() == 0)
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Saat ini tidak ada permintaan barang yang perlu diproses.
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle mr-2"></i>
                        Saat ini terdapat {{ $ordersAdmin->count() }} permintaan barang yang perlu diproses untuk pengeluaran.
                        <a href="{{ route('permintaan-produk.index') }}" class="ml-1" style="color: #0000e6; text-decoration: underline;">Lihat Detail Permintaan</a>
                    </div>
                @endif
            @endif
        </div>
    </div>

    @if (auth()->user()->role->role === 'kepala gudang' || auth()->user()->role->role === 'admin gudang')
        <div class="row">
            <!-- Card Semua Barang -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('barang.index') }}" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-thin fa-cubes"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Semua Barang</h4>
                            </div>
                            <div class="card-body">
                                {{ $barang }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Barang Masuk -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('barang-masuk.index') }}" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-file-import"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Barang Masuk</h4>
                            </div>
                            <div class="card-body">
                                {{ $barangMasuk }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Barang Keluar -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('barang-keluar.index') }}" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Barang Keluar</h4>
                            </div>
                            <div class="card-body">
                                {{ $barangKeluar }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card Pengguna -->
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <a href="{{ route('data-pengguna.index') }}" class="text-decoration-none">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pengguna</h4>
                            </div>
                            <div class="card-body">
                                {{ $user }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Grafik Barang Masuk & Barang Keluar</h4>
                    </div>
                    <div class="card-body">
                        <canvas id="summaryChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Stok Mencapai Batas Minimum</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barangMinimum as $barang)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $barang->kode_barang }}</td>
                                        <td>{{ $barang->nama_barang }}</td>
                                        <td>
                                            @if($barang->status_stok === 'danger')
                                                <span class="badge badge-danger">{{ $barang->stok }}</span>
                                            @elseif($barang->status_stok === 'warning')
                                                <span class="badge badge-warning">{{ $barang->stok }}</span>
                                            @else
                                                {{ $barang->stok }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    @endif


    @if (auth()->user()->role->role === 'admin service')
      <div class="row">
          <div class="col-lg-4 col-md-6 col-sm-6 col-12">
              <div class="card card-statistic-1">
                  <div class="card-icon bg-info">
                      <i class="fas fa-receipt"></i>
                  </div>
                  <div class="card-wrap">
                      <div class="card-header">
                          <h4>Jumlah Permintaan</h4>
                      </div>
                      <div class="card-body">
                          {{ $jumlahPermintaan }}
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-lg-6">
              <x-card title="Chart barang paling populer">
                  <div id="chart-total-sales"></div>
              </x-card>
          </div>
      </div>
    @endif
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    @if (auth()->user()->role->role === 'admin service')
        <script>
          document.addEventListener("DOMContentLoaded", function() {
              const chartContainer = document.getElementById('chart-total-sales');
              const chartData = {
                  total: @json($total),
                  label: @json($label)
              };

              
              if (!chartContainer) {
                  console.error('Chart container not found!');
                  return;
              }

              if (!chartData.total.length || !chartData.label.length) {
                  chartContainer.innerHTML = '<div class="text-center p-3">Tidak ada data untuk ditampilkan</div>';
                  return;
              }

              const options = {
                  series: chartData.total,
                  chart: {
                      width: '100%',
                      type: 'donut',
                  },
                  labels: chartData.label,
                  plotOptions: {
                      pie: {
                          donut: {
                              size: '50%'
                          }
                      }
                  },
                  dataLabels: {
                      enabled: true
                  },
                  legend: {
                      position: 'top'
                  },
              };

              try {
                  const chart = new ApexCharts(chartContainer, options);
                  chart.render().then(() => {
                      console.log('Chart rendered successfully');
                  }).catch(err => {
                      console.error('Error rendering chart:', err);
                  });
              } catch (error) {
                  console.error('Error creating chart:', error);
              }
          });
        </script>
    @endif

    @if (auth()->user()->role->role === 'kepala gudang' || auth()->user()->role->role === 'admin gudang')
        <script>
            var ctx = document.getElementById('summaryChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [
                        @foreach ($barangMasukData as $data)
                            '{{ date('F', strtotime($data->date)) }}',
                        @endforeach
                    ],
                    datasets: [{
                            label: 'Barang Masuk',
                            data: [
                                @foreach ($barangMasukData as $data)
                                    '{{ $data->total }}',
                                @endforeach
                            ],
                            backgroundColor: 'blue'
                        },
                        {
                            label: 'Barang Keluar',
                            data: [
                                @foreach ($barangKeluarData as $data)
                                    '{{ $data->total }}',
                                @endforeach
                            ],
                            backgroundColor: 'red'
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0,
                            stepSize: 1,
                            ticks: {
                                callback: function(value) {
                                    if (value % 1 === 0) {
                                        return value;
                                    }
                                }
                            }
                        }
                    },
                    onClick: function(e) {
                        var activePoints = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);

                        if (activePoints.length > 0) {
                            var clickedDatasetIndex = activePoints[0].datasetIndex;

                            if (clickedDatasetIndex === 0) {
                                // Barang Masuk clicked
                                window.location.href = '/laporan-barang-masuk'; 
                            } else if (clickedDatasetIndex === 1) {
                                // Barang Keluar clicked
                                window.location.href = '/laporan-barang-keluar'; 
                            }
                        }
                    }
                }
            });
        </script>
    @endif
@endpush

