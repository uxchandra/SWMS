<div class="modal fade" tabindex="-1" role="dialog" id="modal_detail_barang">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title"><i class="fas fa-box"></i> Detail Barang</h5>
              <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>

          <form enctype="multipart/form-data">
              <div class="modal-body">
                  <div class="row">
                      <!-- Kolom Kiri -->
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Kode Barang</label>
                              <input type="text" class="form-control" name="kode" id="detail_kode" disabled>
                          </div>

                          <div class="form-group">
                              <label>Nama Barang</label>
                              <input type="text" class="form-control" name="nama_barang" id="detail_nama_barang" disabled>
                          </div>

                          <div class="form-group">
                              <label>Jenis Barang</label>
                              <select class="form-control" name="jenis_id" id="detail_jenis_id" disabled>
                                  @foreach ($jenis_barangs as $jenis)
                                      <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="form-group">
                            <label>Ukuran/size</label>
                            <input type="text" class="form-control" name="size" id="detail_size" disabled>
                          </div>

                          <div class="form-group">
                            <label>Supplier</label>
                            <input type="text" class="form-control" name="nama_supplier" id="detail_nama_supplier" disabled>
                          </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="col-md-6">                      
                          <div class="form-group">
                              <label>Stok Minimum</label>
                              <input type="number" class="form-control" name="stok_minimum" id="detail_stok_minimum" disabled>
                          </div>

                          <div class="form-group">
                            <label>Stok Maksimum</label>
                            <input type="number" class="form-control" name="stok_maximum" id="detail_stok_maximum" disabled>
                          </div>

                          <div class="form-group">
                              <label>Stok Saat Ini</label>
                              <input type="text" class="form-control" name="stok" id="detail_stok" disabled>
                          </div>

                          <div class="form-group">
                              <label>Harga</label>
                              <input class="form-control" name="price" id="detail_price" disabled></input>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="modal-footer bg-light">
                  <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-times"></i> Keluar</button>
              </div>
          </form>
      </div>
  </div>
</div>
