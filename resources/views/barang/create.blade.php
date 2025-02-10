<div class="modal fade" tabindex="-1" role="dialog" id="modal_tambah_barang">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header bg-primary text-white">
              <h5 class="modal-title"><i class="fas fa-box"></i> Tambah Barang</h5>
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
                              <input type="text" class="form-control" name="kode" id="kode" placeholder="Masukkan kode barang" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-kode"></div>
                          </div>

                          <div class="form-group">
                              <label>Nama Barang</label>
                              <input type="text" class="form-control" name="nama_barang" id="nama_barang" placeholder="Masukkan nama barang" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_barang"></div>
                          </div>

                          <div class="form-group">
                              <label>Jenis Barang</label>
                              <select class="form-control" name="jenis_id" id="jenis_id" required>
                                  <option value="" disabled selected>Pilih jenis barang</option>
                                  @foreach ($jenis_barangs as $jenis)
                                      <option value="{{ $jenis->id }}">{{ $jenis->jenis_barang }}</option>
                                  @endforeach
                              </select>
                          </div>

                          <div class="form-group">
                              <label>Ukuran (Size)</label>
                              <input type="text" class="form-control" name="size" id="size" placeholder="Masukkan ukuran barang" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-size"></div>
                          </div>

                          <div class="form-group">
                              <label>Nama Supplier</label>
                              <input type="text" class="form-control" name="nama_supplier" id="nama_supplier" placeholder="Masukkan nama supplier" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-nama_supplier"></div>
                          </div>
                      </div>

                      <!-- Kolom Kanan -->
                      <div class="col-md-6">
                          <div class="form-group">
                              <label>Stok Minimum</label>
                              <input type="number" class="form-control" name="stok_minimum" id="stok_minimum" placeholder="Masukkan stok minimum" min="1" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok_minimum"></div>
                          </div>

                          <div class="form-group">
                              <label>Stok Maksimum</label>
                              <input type="number" class="form-control" name="stok_maximum" id="stok_maximum" placeholder="Masukkan stok maksimum" min="1" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok_maximum"></div>
                          </div>

                          <div class="form-group">
                              <label>Stok Saat Ini</label>
                              <input type="number" class="form-control" name="stok" id="stok" placeholder="Masukkan jumlah stok saat ini" min="0" value="0">
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-stok"></div>
                          </div>

                          <div class="form-group">
                              <label>Harga Barang</label>
                              <input type="text" class="form-control" name="price" id="price" placeholder="Masukkan harga barang" required>
                              <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-price"></div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="modal-footer bg-light">
                  <button type="button" class="btn btn-dark" data-dismiss="modal"><i class="fas fa-times"></i> Keluar</button>
                  <button type="submit" class="btn btn-primary" id="store"><i class="fas fa-save"></i> Tambah</button>
              </div>
          </form>
      </div>
  </div>
</div>
