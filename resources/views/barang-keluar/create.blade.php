@extends('layouts.app')

@section('content')
    <div class="section-header">
        <h1>Scan Barang Keluar</h1>
    </div>
    <div class="container-fluid mt-2">
        <form method="post" action="{{ route('orders.processScan', $order->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <h5>Detail Order</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Kode Barang</th>
                                                <th class="text-center">Nama Barang</th>
                                                <th class="text-center">Stok Saat Ini</th>
                                                <th class="text-center">Qty Diminta</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->orderItems as $item)
                                                <tr>
                                                    <td class="text-center">{{ $item->barang->kode }}</td>
                                                    <td>{{ $item->barang->nama_barang }}</td>
                                                    <td class="text-center">{{ $item->barang->stok }}</td>
                                                    <td class="text-center">{{ $item->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Form Scan -->
                            <div class="form-group row">
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="kode_barang" placeholder="Scan/Input Kode Barang">
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-primary" id="submit_kode">
                                        <i class="fas fa-barcode"></i> Scan
                                    </button>
                                </div>
                            </div>

                            <!-- Tabel Barang yang Di-scan -->
                            <div class="table-responsive mt-4">
                                <table class="table" id="tableItem">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-muted">Kode Barang</th>
                                            <th class="text-center text-muted">Nama Barang</th>
                                            <th class="text-center text-muted">Stok Saat Ini</th>
                                            <th class="text-center text-muted">Qty Keluar</th>
                                            <th class="text-center text-muted">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="tanggal_keluar">Tanggal Keluar</label>
                                <input type="date" class="form-control" id="tanggal_keluar" name="tanggal_keluar" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var rowCounter = 0;

                // Focus pada input kode saat halaman dimuat
                $('#kode_barang').focus();

                // Handle ketika tombol Enter ditekan pada input kode
                $('#kode_barang').keypress(function(e) {
                    if(e.which == 13) { // Enter key
                        e.preventDefault();
                        $('#submit_kode').click();
                    }
                });

                $("#submit_kode").click(function(e) {
                    e.preventDefault();
                    var code = $('#kode_barang').val();
                    
                    if (!code) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan masukkan kode barang!'
                        });
                        return;
                    }

                    var existingRow = $('#tableItem').find('input.code[value="' + code + '"]').closest('tr');
                    
                    if (existingRow.length > 0) {
                        var qtyInput = existingRow.find('input.qty');
                        var currentQty = parseFloat(qtyInput.val());
                        qtyInput.val(currentQty + 1);
                        $('#kode_barang').val('').focus();
                    } else {
                        $.ajax({
                            type: 'GET',
                            url: "{{ url('/barang/kode') }}/" + code,
                            success: function(data) {
                                if (data.success) {
                                    const newRow = `
                                        <tr id="rowItem${rowCounter}">
                                            <td><input type="text" class="form-control code" name="code[]" value="${data.barang.kode}" readonly disabled></td>
                                            <td><input type="text" class="form-control" name="nama_barang[]" value="${data.barang.nama_barang}" readonly disabled></td>
                                            <td><input type="text" class="form-control" name="stok[]" value="${data.barang.stok}" readonly disabled></td>
                                            <td><input type="number" class="form-control qty" name="qty[]" value="1" min="1"></td>
                                            <td>
                                                <button class="btn btn-danger btn-sm delete_row">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <td style="display: none">
                                                <input type="hidden" name="barang_id[]" value="${data.barang.id}">
                                            </td>
                                        </tr>
                                    `;
                                    rowCounter++;
                                    $('#tableItem tbody').append(newRow);
                                    $('#kode_barang').val('').focus();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Oops...',
                                        text: 'Barang tidak ditemukan'
                                    });
                                    $('#kode_barang').val('').focus();
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Terjadi kesalahan saat mencari barang'
                                });
                                $('#kode_barang').val('').focus();
                            }
                        });
                    }
                });

                // Handle delete row
                $('body').on('click', '.delete_row', function(e) {
                    e.preventDefault();
                    $(this).closest("tr").remove();
                    $('#kode_barang').focus();
                });

                // Handle form submit
                $('form').submit(function(e) {
                    if ($('#tableItem tbody tr').length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan tambahkan minimal satu barang!'
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection