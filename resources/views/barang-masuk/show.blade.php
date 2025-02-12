<!-- Modal untuk Detail Barang Masuk -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Barang Masuk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Informasi Transaksi -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <strong>Tanggal Masuk:</strong>
                        <span id="modal-tanggal"></span>
                    </div>
                    <div class="col-md-6">
                        <strong>Input By:</strong>
                        <span id="modal-user"></span>
                    </div>
                </div>

                <!-- Tabel Detail Barang -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody id="modal-items">
                            <!-- Data akan diisi melalui AJAX -->
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