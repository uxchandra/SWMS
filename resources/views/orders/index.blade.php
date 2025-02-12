@extends('layouts.app')
@include('orders.create')

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
                                <th>Requester</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $index => $order)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $order->requester->name }}</td>
                                    <td>{{ $order->department->nama_departemen }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->catatan }}</td>
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
        
        // Inisialisasi Select2 untuk semua dropdown dengan class .select2
        $('.select2').select2({
            placeholder: "Cari barang...", // Placeholder untuk search
            allowClear: true, // Memungkinkan menghapus pilihan
            width: '100%' // Lebar dropdown
        });

        // Tambahkan event listener untuk menambahkan baris baru
        document.getElementById('tambah-barang').addEventListener('click', function () {
            const barangList = document.getElementById('barang-list');
            const newRow = document.querySelector('.barang-row').cloneNode(true);

            // Reset nilai input di baris baru
            newRow.querySelector('.barang-select').selectedIndex = 0;
            newRow.querySelector('.quantity-input').value = '';
            newRow.querySelector('.remove-barang').disabled = false;

            // Inisialisasi Select2 untuk baris baru
            $(newRow).find('.select2').select2({
                placeholder: "Cari barang...",
                allowClear: true,
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

