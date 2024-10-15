<!-- Modal for Create and Edit -->
<div class="modal fade" id="UsersModal" tabindex="-1" aria-labelledby="UsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="userForm">
                    <input type="hidden" id="formUserId" name="id">
                    <div class="mb-3">
                        <label for="formUserName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="formUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formUserEmail" class="form-label">Email address</label>
                        <input type="email" class="form-control" id="formUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="formUserRole" class="form-label">Role</label>
                        <select class="form-control" id="formUpdateUserRole" name="role">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formUserRole" class="form-label">Enable or disabled</label>
                        <select class="form-select" id="formEditUserDisabled" name="is_disabled">
                            <option value="0">Enable Account</option>
                            <option value="1">Disable Account</option>
                        </select>
                    </div>
                    <div class="mb-3" id="update-kasir">
                        <label for="formUserIdShop" class="form-label">Pilih Toko/Kios</label>
                        <select class="form-select" id="formEditUserIdShop" name="id_shop">
                            <option value="">Pilih Toko/Kios</option>
                            @foreach (App\Models\Shop::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="update-gudang">
                        <label for="formUserIdShop" class="form-label">Pilih Gudang</label>
                        <select class="form-select" id="formEditUserIdWirehouse" name="id_wirehouse">
                            <option value="">Pilih Gudang</option>
                            @foreach (App\Models\Wirehouse::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                            @endforeach
                        </select>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveUserBtn">Save</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="UsersModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for Create and Edit -->
                <form id="createUserForm">
                    <div class="mb-3">
                        <label for="formUserName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="formCreateUserName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formUserEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="formCreateUserEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="formUserRole" class="form-label">Role</label>
                        <select class="form-select" id="formCreateUserRole" name="role">
                            <option value="Owner">Owner</option>
                            <option value="Admin">Admin</option>
                            <option value="Gudang">Gudang</option>
                            <option value="Kasir">Kasir</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="formUserRole" class="form-label">Enable or disabled</label>
                        <select class="form-select" id="formUserDisabled" name="is_disabled">
                            <option value="0">Enable Account</option>
                            <option value="1">Disable Account</option>
                        </select>
                    </div>
                    <div class="mb-3" id="create-kasir">
                        <label for="formUserIdShop" class="form-label">Pilih Toko/Kios</label>
                        <select class="form-select" id="formUserIdShop" name="id_shop">
                            <option value="">Pilih Toko/Kios</option>
                            @foreach (App\Models\Shop::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="create-gudang">
                        <label for="formUserIdShop" class="form-label">Pilih Gudang</label>
                        <select class="form-select" id="formUserIdWirehouse" name="id_wirehouse">
                            <option value="">Pilih Gudang</option>
                            @foreach (App\Models\wirehouse::all() as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} - {{ $item->address }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createUserBtn">Save</button>
            </div>
        </div>
    </div>
</div>
