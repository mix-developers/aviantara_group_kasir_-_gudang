@extends('layouts.backend.admin_no_navbar')

@section('content')
    <div class="mx-2">
        <div class="row">
            {{-- //produk --}}
            <div class="col-lg-9">
                <div class="row justify-content-between">
                    <div class="col">
                        <span class="h2 text-danger">{{ Auth::user()->name }}</span><br><small>Kasir Toko</small>
                    </div>
                    <div class="col text-end">
                        <span id="time" class="text-danger h2"></span><br>
                        <small>{{ date('d F Y') }}</small>
                    </div>
                    <hr class="my-2">
                </div>
                <div class="d-flex mb-3">
                    <button class="btn btn-primary mx-2 px-1" style="width: 200px;"><i class='bx bx-search'></i>Cari
                        Barang</button>
                    <input type="search" class="form-control mx-2" placeholder="Scan Barcode" autofocus>
                    <input type="number" class="form-control mx-2" value="1" style="width: 100px;">
                </div>

                <strong>Daftar Pembelian :</strong>
                <div class="d-flex my-3 p-2 justify-content-between bg-white align-items-center fw-bold text-primary shadow-sm text-uppercase"
                    style="border-radius: 10px;">
                    <span>NO</span>
                    <span>Nama Barang</span>
                    <span>Jumlah</span>
                    <span>Subtotal</span>
                    <span><i class="bx bx-trash p-2 bg-danger text-white" style="border-radius: 10px;"></i></span>
                </div>
                <div style="max-height: 350px; overflow-y: auto; margin-bottom:10px;">

                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                    <div class="d-flex my-2 py-1 px-2 justify-content-between bg-primary align-items-center fw-bold text-white shadow-sm"
                        style="border-radius: 10px;">
                        <span>#1</span>
                        <span>AFCO USUS BERSIH</span>
                        <span>10 Pcs</span>
                        <span>Rp 100.000</span>
                        <button class="btn btn-sm btn-light" style="border-radius: 10px;"><i
                                class="bx bx-trash text-danger"></i></span>
                    </div>
                </div>
            </div>
            {{-- rincian --}}
            <div class="col-lg-3">
                <div class="card bg-primary text-white p-0 mb-3">
                    <div class="card-body p-2">
                        <span class="fw-bold" style="font-size: 12px;">TOTAL</span><br>
                        <span class="h2 fw-bold text-white">Rp 100.000</span>
                    </div>
                </div>
                <div class="card bg-warning text-white p-0 mb-3">
                    <div class="card-body p-2">
                        <span class="fw-bold" style="font-size: 12px;">KEMBALIAN</span><br>
                        <span class="h2 fw-bold text-white">Rp 0</span>
                    </div>
                </div>
                <div class="mb-3">

                    <span class="fw-bold">Dibayarkan</span>
                    <input type="number" class="form-control" id="bayar" name="bayar" min="1" required>
                    <hr>
                    <select name="" id="" class="form-select">
                        <option value="">Tunai</option>
                        <option value="">Transfer</option>
                    </select>
                </div>
                <div class="card bg-warning text-white p-0 mb-3">
                    <div class="card-body p-2">
                        <select name="" id="" class="form-select mb-3">
                            <option value="">Metode Diskon</option>
                            <option value="">Transfer</option>
                        </select>
                        <input type="number" class="form-control" id="bayar" name="bayar" min="0"
                            value="0" required>
                    </div>
                </div>
                <div class="d-flex">
                    <button class="btn btn-lg btn-danger d-block w-100 fw-bold px-1"><i class="bx bx-md bx-save"></i>
                        Simpan
                        Transaksi</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function updateTime() {
            const now = new Date();

            // Format jam
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12; // Convert to 12-hour format and adjust for 12:00

            // Format tanggal
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            const formattedDate = now.toLocaleDateString('en-US', options);

            // Update HTML
            document.getElementById('time').textContent = `${hours}:${minutes} ${ampm}`;

        }

        // Perbarui setiap detik
        setInterval(updateTime, 1000);

        // Inisialisasi saat halaman dimuat
        updateTime();
    </script>
@endpush
