 {{-- custom alert --}}
 <div id="customAlert" class="custom-alert d-none">
     <div class="custom-alert-box">
         <p id="customAlertMessage"></p>
         <button onclick="closeCustomAlert()" class="btn btn-primary btn-sm mt-2">OK</button>
     </div>
 </div>
 {{-- custom confirm --}}
 <div id="customConfirm" class="custom-alert d-none">
     <div class="custom-alert-box">
         <p id="customConfirmMessage">Apakah Anda yakin ingin menghapus semua item dari daftar pembelian?</p>
         <div class="d-flex justify-content-center gap-2 mt-3">
             <button onclick="confirmYes()" class="btn btn-danger btn-sm">Ya</button>
             <button onclick="confirmNo()" class="btn btn-secondary btn-sm">Batal</button>
         </div>
     </div>
 </div>
