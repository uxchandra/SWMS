<div class="modal fade" tabindex="-1" role="dialog" id="modal_edit_pengguna">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form enctype="multipart/form-data">
          <div class="modal-body">

            <input type="hidden" id="pengguna_id">
            <div class="form-group">
                <label>Nama</label>
                <input type="text" class="form-control" name="name" id="edit_name">
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-name"></div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" id="edit_username" >
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-username"></div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" id="edit_password">
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-password"></div>
            </div>
            <div class="form-group">
                <label>Pilih Role</label>
                  <select class="form-control" name="role" id="edit_role_id" style="width: 100%">
                    @foreach ($roles as $role)
                        @if (old('role_id', $role->role) == $role->id)
                        <option value="{{ $role->id }}" selected>{{ $role->role }}</option>
                        @else
                        <option value="{{ $role->id }}">{{ $role->role }}</option>
                        @endif
                    @endforeach
                  </select>
                  <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-role"></div>
            </div>
            <div class="form-group">
              <label>Pilih Department</label>
                <select class="form-control" name="role" id="edit_department_id" style="width: 100%">
                  @foreach ($departments as $department)
                      @if (old('department_id', $department->nama_departemen) == $department->id)
                      <option value="{{ $department->id }}" selected>{{ $department->nama_departemen }}</option>
                      @else
                      <option value="{{ $department->id }}">{{ $department->nama_departemen }}</option>
                      @endif
                  @endforeach
                </select>
                <div class="alert alert-danger mt-2 d-none" role="alert" id="alert-role"></div>
            </div>
        
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-dark" data-dismiss="modal">Keluar</button>
          <button type="button" class="btn btn-primary" id="update">Edit</button>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>



