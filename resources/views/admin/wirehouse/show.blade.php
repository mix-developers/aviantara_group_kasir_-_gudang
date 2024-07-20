@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="card mb-3">
                <div class="card-header">
                    Informasi Gudang
                </div>
                <table class="table table-hover">
                    <tr>
                        <td class="fw-bold">Nama Gudang</td>
                        <td>:</td>
                        <td class="text-primary">{{ $wirehouse->name }}</td>
                    </tr>
                    <tr>
                        <td class="fw-bold">Alamat Gudang</td>
                        <td>:</td>
                        <td>{{ $wirehouse->address }}</td>
                    </tr>
                </table>
            </div>
            <div class="card mb-3">
                <div class="card-header">
                    Pegawai Gudang
                </div>
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $itemStaff)
                            <tr>
                                <td>{{ $itemStaff->id }}</td>
                                <td><strong>{{ $itemStaff->name }}</strong><br><small
                                        class="text-mutted">{{ $itemStaff->email }}</small></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header flex-column flex-md-row">
                    <div class="head-label ">
                        <h5 class="card-title mb-0">{{ $title ?? 'Title' }}</h5>
                    </div>
                    <div class="dt-action-buttons text-end pt-3 pt-md-0">
                        <div class=" btn-group " role="group">
                            <button class="btn btn-secondary refresh btn-default" type="button">
                                <span>
                                    <i class="bx bx-sync me-sm-1"> </i>
                                    <span class="d-none d-sm-inline-block"></span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-datatable table-responsive">
                    <table id="datatable-product" class="table table-hover table-bordered display table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Gudang</th>
                                <th>Stok</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('js')
    <script>
        $(function() {
            $('#datatable-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('wirehouse-detail-datatable', $wirehouse->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'wirehouse',
                        name: 'wirehouse'
                    },
                    {
                        data: 'stok',
                        name: 'stok'
                    },
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-product').DataTable().ajax.reload();
            });


        });
    </script>
@endpush
