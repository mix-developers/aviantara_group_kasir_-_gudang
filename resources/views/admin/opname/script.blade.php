@push('js')
    <script>
        $(function() {
            $('#datatable-wirehouse').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('wirehouses-datatable') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },

                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'last_opname',
                        name: 'last_opname',
                    },
                    {
                        data: 'schedule',
                        name: 'schedule',
                    },

                    {
                        data: 'action_opname',
                        name: 'action_opname'
                    }
                ]
            });
            window.schedule = function(id) {
                $('#idWirehouse').val(id);
                $('#scheduleModal').modal('show');
            };
            $('.refresh').click(function() {
                $('#datatable-wirehouse').DataTable().ajax.reload();
                getWirehouseCard().ajax.reload();
            });
            $('#createScheduleBtn').click(function() {
                $('#createScheduleBtnSpinner').show();
                $('#createScheduleBtn').prop('disabled', true);
                var formData = $('#schedulaOpnameForm').serialize();

                $.ajax({
                    type: 'POST',
                    url: '/opname-wirehouse-schedule/store',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#createScheduleBtnSpinner').hide();
                        $('#createScheduleBtn').prop('disabled', false);
                        $('#datatable-payment').DataTable().ajax.reload();
                        getAlert(response.message);
                        $('#datatable-wirehouse').DataTable().ajax.reload();
                        $('#scheduleModal').modal('hide');
                    },
                    error: function(xhr) {
                        $('#createScheduleBtnSpinner').hide();
                        $('#createScheduleBtn').prop('disabled', false);
                        alert('Terjadi kesalahan: ' + xhr.responseText);
                    }
                });
            });

            function getAlert(alertValue) {
                $('#alert').append(
                    '<div class="alert alert-success alert-dismissible" role="alert">' +
                    alertValue +
                    '<button type = "button" class = "btn-close"  data-bs-dismiss="alert" aria - label = "Close" ></button> </div>'
                )
            }
            getWirehouseCard();
        });
    </script>
@endpush
