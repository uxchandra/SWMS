@extends('layouts.app')
@include('orders.create')
@include('orders.show')

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
                                    <th>Total Item</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $index => $order)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $order->created_at->format('H:i') }}</td>
                                        <td>{{ $order->orderItems->count() }}</td>
                                        <td>{{ $order->status }}</td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal{{ $index }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <p>Belum ada data permintaan barang</p>
                    </div>
                @endif
            </div>
        </div>
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

