@extends('layouts.backend.admin')

@section('content')
    <div class="text-center my-4">
        <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 30vh;"><br>
        <span class="text-warning h1">AVIANTARA</span> <span class="h4">GROUP</span>
        <hr>
    </div>
    <div class="my-3 text-center">
        <h4>Selamat datang kembali di <span class="text-primary">Sistem Informasi Manajemen Gudang dan
                Kios</span>
        </h4>
        @if (Auth::user()->role == 'Gudang')
            <p class="badge bg-label-danger"><i class="bx bx-error"></i> Harap selalu cek fisik barang kadaluarsa pada gudang
                dan sistem</p>
        @endif
    </div>
@endsection
