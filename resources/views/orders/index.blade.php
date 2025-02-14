@extends('layouts.app')
@include('orders.create')
@include('orders.show')
@include('orders.approve')

@section('content')
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Data Permintaan Barang</h1>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addOrderModal">
            <i class="fas fa-plus"></i> Buat Permintaan
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            @if($orders && $orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Department</th>
                                <th>Total Item</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $index => $order)
                                @php
                                    $statusColors = [
                                        'Pending' => 'warning',
                                        'Approved by Kadiv' => 'info',
                                        'Approved by Kagud' => 'primary',
                                        'Ready' => 'success',
                                        'Completed' => 'dark',
                                    ];
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->created_at->translatedFormat('d F Y') }}</td>
                                    <td>{{ $order->created_at->format('H:i') }}</td>
                                    <td>{{ $order->department->nama_departemen }}</td>
                                    <td>{{ $order->orderItems->count() }}</td>
                                    <td>
                                        <span class="badge badge-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            {{ $order->keterangan }}
                                        </small>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal{{ $index }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        @switch($order->status)
                                            @case('Pending')
                                                @if(auth()->user()->role->role === 'kepala divisi')
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal{{ $order->id }}">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                @endif
                                                @break
                                            @case('Approved by Kadiv')
                                                @if(auth()->user()->role->role === 'kepala gudang')
                                                    <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#approveModal{{ $order->id }}">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                @endif
                                                @break
                                            @case('Approved by Kagud')
                                                @if(auth()->user()->role->role === 'admin gudang')
                                                    <a href="{{ route('orders.scan', $order->id) }}" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-barcode"></i> Input
                                                    </a>
                                                @endif
                                                @break
                                            @case('Ready')
                                                @if(auth()->user()->role->role === 'admin gudang')
                                                    <form action="{{ route('orders.complete', $order->id) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check"></i> Complete
                                                        </button>
                                                    </form>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-box-open fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">Belum ada data permintaan barang</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .badge {
            font-weight: 500;
            font-size: 12px;
            padding: 5px 10px;
        }
        .badge-warning { background-color: #ffa500; color: #fff; }
        .badge-info { background-color: #17a2b8; }
        .badge-primary { background-color: #007bff; }
        .badge-success { background-color: #28a745; }
        .badge-dark { background-color: #343a40; }
        .text-muted { font-size: 13px; }
        .table td { vertical-align: middle; }
    </style>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Tambahkan event listener untuk menambahkan baris baru
        document.getElementById('tambah-barang').addEventListener('click', function () {
            const barangList = document.getElementById('barang-list');
            const newRow = document.querySelector('.barang-row').cloneNode(true);

            // Reset nilai input di baris baru
            newRow.querySelector('.barang-select').selectedIndex = 0;
            newRow.querySelector('.quantity-input').value = '';
            newRow.querySelector('.remove-barang').disabled = false;

            // Inisialisasi Select2 untuk baris baru
            $(newRow).find('.barang-select').select2({
                width: '100%'
            });

            // Tambahkan baris baru ke daftar
            barangList.appendChild(newRow);
        });

        // Tombol untuk menghapus baris barang
        $(document).on('click', '.remove-barang', function () {
            if ($('.barang-row').length > 1) {
                $(this).closest('.barang-row').remove();
            }
        });

        // Validasi stok saat memilih barang
        document.addEventListener('change', function (e) {
            if (e.target.classList.contains('barang-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const stok = selectedOption.getAttribute('data-stok');
                const quantityInput = e.target.closest('.barang-row').querySelector('.quantity-input');

                if (stok) {
                    quantityInput.setAttribute('max', stok);
                    if (quantityInput.value > stok) {
                        alert('Stok tidak mencukupi!');
                        quantityInput.value = '';
                    }
                }
            }
        });
    });
</script>

