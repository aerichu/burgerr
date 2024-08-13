<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h4>Upload Bukti Pembayaran</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('home/aksi_e_berkas') ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="buktiFile">Upload Bukti Pembayaran</label>
        <input type="file" class="form-control" id="buktiFile" name="bukti_file" required>
    </div>
    <input type="hidden" name="id" value="<?= $php->id_transaksi ?>"> <!-- Ensure this is correct -->
    <button type="submit" class="btn btn-primary">Upload</button>
</form>

        </div>
    </div>
</div>
