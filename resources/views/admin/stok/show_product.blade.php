@extends('layouts.backend.admin')

@section('content')
    @include('layouts.backend.alert')
    <div class="row justify-content-center">
        <div class="col-md-4 mb-4">
            <div class="mb-3">
                <div class="card">
                    <div class="card-body">
                        <img class="card-img-top"
                            src="{{ $product->photo != null ? Storage::url($product->photo) : asset('img/default.webp') }}"
                            alt="..." style="width: 100%; height:200px; object-fit:cover;" />
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Informasi Produk
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <tr>
                            <td class="fw-bold">Nama Produk</td>
                            <td>:</td>
                            <td class="text-primary">{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jenis Satuan</td>
                            <td>:</td>
                            <td>{{ $product->unit }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Isi per-{{ $product->unit }}</td>
                            <td>:</td>
                            <td>{{ $product->quantity_unit }} {{ $product->sub_unit }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Gudang</td>
                            <td>:</td>
                            <td>{{ $product->wirehouse->name }}<br><small
                                    class="text-muted">{{ $product->wirehouse->address }}</small></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Stok</td>
                            <td>:</td>
                            <td>
                                @if (app\Models\Product::getStok($product->id) == 0)
                                    <span class="text-danger">Stok Belum tersedia</span>
                                @else
                                    <i
                                        class="bx @if (App\Models\ProductStok::where('id_product', $product->id)->latest()->first()->type == 'Keluar') bx-down-arrow-alt text-danger @else bx-up-arrow-alt text-primary @endif"></i>
                                    <span
                                        class="h4 {{ app\Models\Product::getStok($product->id) == 0 ? 'text-danger' : 'text-primary' }}">
                                        {{ app\Models\Product::getStok($product->id) == 0 ? 'Stok Habis' : number_format(app\Models\Product::getStok($product->id)) . ' ' . $product->unit }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                </div>
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
                    <table id="datatable-detail-product" class="table table-hover table-bordered display">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><i class="bx bx-info-circle"></i></th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                <th>Pegawai</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th><i class="bx bx-info-circle"></i></th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Kadaluarsa</th>
                                <th>Pegawai</th>
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
            $('#datatable-detail-product').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: '{{ url('product-detail-datatable', $product->id) }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'warning',
                        name: 'warning'
                    },
                    {
                        data: 'produk',
                        name: 'produk'
                    },

                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'expired_date',
                        name: 'expired_date'
                    },
                    {
                        data: 'user',
                        name: 'user'
                    },
                ]
            });

            $('.refresh').click(function() {
                $('#datatable-detail-product').DataTable().ajax.reload();
            });

            function getUnitOptions(unitValue) {
                var staticData = ['Karung', 'Karton', 'Bal', 'Pak'];

                $('#formProductUnit').empty();

                $.each(staticData, function(index, unit) {
                    var selected = (unit === unitValue) ? 'selected' : '';
                    $('#formProductUnit').append('<option value="' + unit + '" ' + selected + '>' +
                        unit +
                        '</option>');
                });
            }


        });
    </script>
@endpush
