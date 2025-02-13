<!-- Modal -->
<div class="modal fade" id="addOrderModal" role="dialog" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Buat Permintaan Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form untuk membuat permintaan -->
                <form id="createOrderForm" action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    <!-- Daftar Barang yang Diminta -->
                    <div class="form-group">
                        <label>Barang yang Diminta</label>
                        <div id="barang-list">
                            <!-- Baris pertama untuk input barang -->
                            <div class="row mb-2 barang-row">
                                <div class="col-md-6">
                                    <select class="form-control barang-select" name="barang_id[]" required>
                                        <option value="">Cari Barang</option>
                                        @foreach($barangs as $barang)
                                            <option value="{{ $barang->id }}" data-stok="{{ $barang->stok }}">
                                                {{ $barang->nama_barang }} (Stok: {{ $barang->stok }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" class="form-control quantity-input" name="quantity[]" min="1" placeholder="Quantity" required>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger btn-sm remove-barang" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="department_id" value="{{ auth()->user()->department_id }}">
                        <!-- Tombol untuk menambah baris barang -->
                        <button type="button" class="btn btn-dark btn-sm" id="tambah-barang">
                            <i class="fas fa-plus"></i> Tambah Barang
                        </button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Tutup</button>
                <button type="submit" form="createOrderForm" class="btn btn-primary">Simpan Permintaan</button>
            </div>
        </div>
    </div>
</div>