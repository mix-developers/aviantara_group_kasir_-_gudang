 <!-- Modal riwayat Penjualan -->
 <div class="modal fade" id="modalRiwayatPenjualan" tabindex="-1" aria-labelledby="modalCariBarangLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalCariBarangLabel">Riwayat Penjualan</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="table-responsive">
                     <table id="historyTable" class="table table-striped table-sm">
                         <thead>
                             <tr>
                                 <th>Cetak</th>
                                 <th>Tanggal</th>
                                 <th>Invoice</th>
                                 <th>Sub total</th>
                                 <th>Dibayarkan</th>
                                 <th>Kembalian</th>
                             </tr>
                         </thead>
                         <tbody>
                             <!-- Data akan diisi dengan AJAX -->
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <!-- Modal Cari Barang -->
 <div class="modal fade" id="modalCariBarang" tabindex="-1" aria-labelledby="modalCariBarangLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="modalCariBarangLabel">Cari Barang</h5>
                 <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
             </div>
             <div class="modal-body">
                 <div class="table-responsive">

                     <table id="productTable" class="table table-striped table-sm">
                         <thead>
                             <tr>
                                 <th>Pilih</th>
                                 <th>Nama Barang</th>
                                 <th>Harga</th>
                                 <th>Stok</th>
                             </tr>
                         </thead>
                         <tbody>
                             <!-- Data akan diisi dengan AJAX -->
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>
 </div>
