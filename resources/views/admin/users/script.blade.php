@push('js')
    <script>
        $(function() {
            $('#datatable-users').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('users-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'avatar',
                        name: 'avatar'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },

                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'last_login',
                        name: 'last_login'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ]
            });
            $('.create-new').click(function() {
                $('#create').modal('show');

                // Inisialisasi untuk menyembunyikan form create-kasir dan create-gudang
                $('#create-kasir').hide();
                $('#create-gudang').hide();

                // Event listener untuk perubahan pada formUserRole
                $('#formCreateUserRole').change(function() {
                    var selectedRole = $(this).val();

                    if (selectedRole === 'Kasir') {
                        $('#create-kasir').show();
                        $('#create-gudang').hide();
                    } else if (selectedRole === 'Gudang') {
                        $('#create-kasir').hide();
                        $('#create-gudang').show();
                    } else {
                        $('#create-kasir').hide();
                        $('#create-gudang').hide();
                    }
                });
            });

            function getRoleOptions(roleValue) {
                var staticData = ['Owner', 'Admin', 'Gudang', 'Kasir'];

                $('#formUpdateUserRole').empty();

                $.each(staticData, function(index, role) {
                    var selected = (role === roleValue) ? 'selected' : '';
                    $('#formUpdateUserRole').append('<option value="' + role + '" ' + selected + '>' +
                        role +
                        '</option>');
                });
            }
            window.editUser = function(id) {
                $.ajax({
                    type: 'GET',
                    url: '/users/edit/' + id,
                    success: function(response) {
                        $('#UsersModalLabel').text('Edit User');
                        $('#formUserId').val(response.id);
                        $('#formUserName').val(response.name);
                        $('#formUserEmail').val(response.email);
                        $('#formEditUserDisabled').val(response.is_disabled);
                        $('#formEditUserIdShop').val(response.id_shop);
                        $('#formEditUserIdWirehouse').val(response.id_wirehouse);
                        getRoleOptions(response.role);
                        $('#UsersModal').modal('show');
                        // Inisialisasi untuk menyembunyikan form create-kasir dan create-gudang
                        $('#update-kasir').hide();
                        $('#update-gudang').hide();
                        if (response.role === 'Kasir') {
                            $('#update-kasir').show();
                            $('#update-gudang').hide();
                        } else if (response.role === 'Gudang') {
                            $('#update-kasir').hide();
                            $('#update-gudang').show();
                        }

                        // Event listener untuk perubahan pada formUserRole
                        $('#formUpdateUserRole').change(function() {
                            var selectedRole = $(this).val();

                            if (selectedRole === 'Kasir') {
                                $('#update-kasir').show();
                                $('#update-gudang').hide();
                            } else if (selectedRole === 'Gudang') {
                                $('#update-kasir').hide();
                                $('#update-gudang').show();
                            } else {
                                $('#update-kasir').hide();
                                $('#update-gudang').hide();
                            }
                        });
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            };
            $('#saveUserBtn').click(function() {
                var formData = $('#userForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/users/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        // Refresh DataTable setelah menyimpan perubahan
                        $('#datatable-users').DataTable().ajax.reload();
                        $('#UsersModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#resetPassword').click(function() {
                var formData = $('#userForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/users/reset',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#datatable-users').DataTable().ajax.reload();
                        $('#UsersModal').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });
            $('#createUserBtn').click(function() {
                var formData = $('#createUserForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/users/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#userssModalLabel').text('Edit User');
                        $('#formCreateUserName').val('');
                        $('#formCreateUserEmail').val('');
                        $('#datatable-users').DataTable().ajax.reload();
                        $('#create').modal('hide');
                    },
                    error: function(xhr) {
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });


            window.deleteUser = function(id) {
                if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
                    $.ajax({
                        type: 'DELETE',
                        url: '/users/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            alert(response.message);
                            // Refresh DataTable setelah menghapus pengguna
                            $('#datatable-users').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            alert('Terjadi kesalahan: ' + xhr.responseText);
                        }
                    });
                }
            };
        });
    </script>
@endpush
