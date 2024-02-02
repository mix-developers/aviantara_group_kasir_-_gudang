@extends('layouts.auth.app')

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="text-center">
                <img src="{{ asset('img/') }}/logo.png" alt="logo" style="width: 20vh;" class="text-center">
            </div>
            <div class="app-brand justify-content-center " style="line-height:2; margin-bottom:10px;">
                <a href="{{ url('/invoice') }}" class="app-brand-link gap-2">
                    <span class="app-brand-logo demo">
                    </span>
                    <span class="app-brand-text demo text-body "><span class="fw-bolder text-warning">AVIANTARA</span>
                        <small style="font-size: 20px;"> GROUP</small></span>
                </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-2">Cek Invoice</h4>
            <p class="mb-4">Silahkan isi nomor invoice di bawah terlebih dahulu</p>
            <form action="" method="GET" enctype="multipart/form-data">
                <div class="mb-3">
                    <input type="text" class="form-control" id="email" name="email" placeholder="Nomor Invoice"
                        autofocus />
                </div>
            </form>
            <hr>
            <p class="text-center">
                <span>Ingin melakukan login ?</span>
                <a href="{{ route('login') }}">
                    <span>Klik di sini</span>
                </a>
            </p>
        </div>
    </div>
@endsection
