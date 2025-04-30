<div id="strukArea" style="display:none;">
    <div style="width: 300px; font-size: 12px; font-family: monospace;">
        <center>
            <h4>{{ env('APP_NAME') }} <br> {{ $shop->name }}</h4>
            <p>{{ $shop->address }}</p>
            <p>Kasir : {{ Auth::user()->name }}</p>
            <p>{{ date('d-m-Y H:i:s') }}</p>
            <hr>
        </center>
        <div id="strukItems"></div>
        <hr>
        <div style="display: flex; justify-content: space-between;">
            <strong>Total:</strong>
            <span id="strukTotal"></span>
        </div>
        <div style="display: flex; justify-content: space-between;">
            <span>Dibayar:</span>
            <span id="strukBayar"></span>
        </div>

        <div style="display: flex; justify-content: space-between;">
            <span>Kembalian:</span>
            <span id="strukKembali"></span>
        </div>
        <hr>
        <div style="display: flex; justify-content: space-between;">
            <span>Metode:</span>
            <span id="strukMetode"></span>
        </div>
        <center>
            <hr>
            <p>Terima kasih!</p>
        </center>
    </div>
</div>
