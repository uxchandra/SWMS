@extends('layouts.app')
@section('content')
    <div class="section-header">
        <h1>Tambah Barang Masuk</h1>
    </div>
    <div class="container-fluid mt-2">
        <form method="post" action="{{ url('/barang-masuk') }}">
            @csrf
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="card">
                        <div class="mt-4 p-4">
                            <div class="table-responsive">
                                <table class="table" id="tableItem">
                                    <thead>
                                        <tr>
                                            <th class="text-center text-muted">Kode Barang</th>
                                            <th class="text-center text-muted">Nama Barang</th>
                                            <th class="text-center text-muted">Qty</th>
                                            <th class="text-center text-muted">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card">
                        <div class="mt-4 p-4">
                            @csrf
                            <div class="form-group">
                                <label for="tanggal_masuk">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="tanggal_masuk" name="tanggal_masuk" value="{{ date('Y-m-d') }}" disabled>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('script')
        <script>
            $(document).ready(function() {
                var rowCounter = 0;

                $("#submit_kode").click(function(e) {
                    e.preventDefault();
                    var code = $('#kode_barang').val();
                    var existingRow = $('#tableItem').find('input.code[value="' + code + '"]').closest('tr');
                    
                    if (existingRow.length > 0) {
                        var qtyInput = existingRow.find('input.qty');
                        var currentQty = parseFloat(qtyInput.val());
                        qtyInput.val(currentQty + 1);
                        $('#kode_barang').val(null);
                    } else {
                        $.ajax({
                            type:'GET',
                            url:"{{ url('/barang/kode') }}/" + code,
                            success:function(data){
                                if (data.success) {
                                    const newRow = `
                                        <tr id="rowItem${rowCounter}">
                                            <td><input type="text" class="form-control code" name="code[]" value="${data.barang.kode}" readonly></td>
                                            <td><input type="text" class="form-control" name="nama_barang[]" value="${data.barang.nama}" readonly></td>
                                            <td><input type="text" class="form-control" name="stok[]" value="${data.barang.stok}" readonly></td>
                                            <td><input type="number" class="form-control qty" name="qty[]" value="1"></td>
                                            <td><button class="btn btn-danger btn-sm delete_row"><i class="fa fa-trash"></i></button></td>
                                            <td style="display: none"><input type="hidden" name="barang_id[]" value="${data.barang.id}"></td>
                                        </tr>
                                    `;
                                    rowCounter++;
                                    $('#tableItem tbody').append(newRow);
                                    $('#kode_barang').val(null);
                                } else {
                                    Swal.fire({icon: 'error', title: 'Oops...', text: 'Barang tidak ditemukan'});
                                    setTimeout(function() { Swal.close(); }, 1000);
                                    $('#kode_barang').val(null);
                                }
                            }
                        });
                    }
                });

                $('body').on('click', '.delete_row', function (event){
                    event.preventDefault();
                    $(this).closest("tr").remove();
                });
            });
        </script>
    @endpush
@endsection
