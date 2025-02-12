@extends('layouts.app')
@include('barang-masuk.show')

@section('content')
    <div class="section-header d-flex justify-content-between align-items-center">
        <h1>Data Barang Masuk</h1>
        <a href="{{ route('barang-masuk.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Barang Masuk
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($barangMasuk && $barangMasuk->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Masuk</th>
                                <th>Jumlah Item</th>
                                <th>Total Quantity</th>
                                <th>Input By</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangMasuk as $index => $transaksi)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</td>
                                    <td>{{ $transaksi->items_count }} item</td>
                                    <td>{{ $transaksi->total_quantity }} pcs</td>
                                    <td>{{ $transaksi->user_name }}</td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm detail-btn" data-id="{{ $transaksi->id }}">
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
                    <p>Belum ada data barang masuk</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
    $(document).ready(function() {
        $('.detail-btn').on('click', function() {
            const id = $(this).data('id');
            
            // Show loading state
            $('#modal-items').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
            $('#detailModal').modal('show');

            // Fetch detail data
            $.ajax({
                url: `/barang-masuk/${id}/detail`,
                method: 'GET',
                success: function(response) {
                    // Update modal content
                    $('#modal-tanggal').text(response.tanggal_masuk);
                    $('#modal-user').text(response.user_name);

                    // Generate items table
                    let itemsHtml = '';
                    response.items.forEach((item, index) => {
                        itemsHtml += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${item.kode_barang}</td>
                                <td>${item.nama_barang}</td>
                                <td>${item.quantity}</td>
                            </tr>
                        `;
                    });
                    $('#modal-items').html(itemsHtml);
                },
                error: function() {
                    $('#modal-items').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat data</td></tr>');
                }
            });
        });
    });
    </script>
@endpush