@foreach($orders as $index => $order)
    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal{{ $index }}" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel{{ $index }}" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $index }}">Detail Permintaan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Requester :</strong> {{ $order->requester->name }}</p>
                            <p><strong>Department :</strong> {{ $order->department->nama_departemen }}</p>
                            <p><strong>Status :</strong> {{ $order->status }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tanggal Request :</strong> {{ $order->created_at->translatedFormat('d F Y') }}</p>
                            <p><strong>Waktu :</strong> {{ $order->created_at->format('H:i') }}</p>
                            <p><strong>Catatan :</strong> {{ $order->catatan ?: '-' }}</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $itemIndex => $item)
                                    <tr>
                                        <td>{{ $itemIndex + 1 }}</td>
                                        <td>{{ $item->barang->nama_barang }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->barang->satuan }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach