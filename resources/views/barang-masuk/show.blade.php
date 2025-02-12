@foreach($barangMasuk as $index => $transaksi)
    <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $index }}">Detail Barang Masuk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Tanggal Masuk:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal_masuk)->format('d F Y') }}</p>
                            <p><strong>Input By:</strong> {{ $transaksi->user->name }}</p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Quantity</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaksi->barang as $itemIndex => $item)
                                    <tr>
                                        <td>{{ $itemIndex + 1 }}</td>
                                        <td>{{ $item->nama_barang }}</td>
                                        <td>{{ $item->quantity }} pcs</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach