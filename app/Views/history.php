<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History</title>
    
    <!-- Include CSS Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Include Custom CSS -->
    <link href="path/to/your/custom.css" rel="stylesheet">
    
    <style>
        /* Custom Styles */
        body {
            background-color: #f8f9fa;
        }
        .nota {
            width: 100%;
            max-width: 1200px; /* Increased width for wider background */
            margin: 20px auto;
            padding: 30px; /* Increased padding for better spacing */
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            background-color: #fff;
        }
        .nota h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .nota table {
            width: 100%;
            margin-bottom: 20px;
        }
        .nota .table th, .nota .table td {
            text-align: center;
            vertical-align: middle;
        }
        .nota .total {
            font-weight: bold;
        }
        .modal-content {
            border-radius: 10px;
        }
        .modal-header {
            border-bottom: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .modal-body {
            padding: 20px;
        }
        .modal-footer {
            border-top: 1px solid #ddd;
            background-color: #f8f9fa;
        }
        .modal-title {
            font-size: 18px;
            font-weight: bold;
        }
        .table thead th {
            background-color: #f1f1f1;
        }
        .table td, .table th {
            padding: 10px;
        }
        .btn-details, .btn-finish, .btn-rate {
            font-size: 14px;
            padding: 5px 10px;
            margin: 0;
        }
        .btn-rate {
            padding: 4px 8px;
        }
        .btn-details i, .btn-finish i, .btn-rate i {
            margin-right: 3px;
        }
        .form-group {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Nota Heading -->
        <div class="nota">
            <h1>History</h1>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama User</th>
                        <th scope="col">Kode Pembelian</th>
                        <th scope="col">Tanggal Pembelian</th>
                        <th scope="col">Total Harga</th>
                        <th scope="col">Makanan</th>
                        <th scope="col">Rating</th>
                        <th scope="col">Status</th>
                        <th scope="col">Upload Bukti</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach($grouped_jel as $kode_transaksi => $transactions): ?>
                    <?php 
                    $total_harga = 0;
                    foreach($transactions as $transaction) {
                        $total_harga += $transaction->total_harga;
                    }
                    ?>
                    <?php foreach($transactions as $index => $kin): ?>
                        <tr>
                            <?php if ($index === 0): ?>
                                <!-- Displaying information only on the first row of each kode_transaksi group -->
                                <td rowspan="<?= count($transactions) ?>"><?= $no++ ?></td>
                                <td rowspan="<?= count($transactions) ?>"><?= $kin->username ?></td>
                                <td rowspan="<?= count($transactions) ?>"><?= $kin->kode_transaksi ?></td>
                                <td rowspan="<?= count($transactions) ?>"><?= $kin->tgl_transaksi ?></td>
                                <td rowspan="<?= count($transactions) ?>"><?= number_format($total_harga, 2, ',', '.') ?></td>
                            <?php endif; ?>
                            
                            <td><?= $kin->nama ?></td>
                            <td><?= $kin->rating ?></td>
                            <td><?= $kin->status ?></td>
                            <td>
                                <?php if ($index === 0): ?>
                                    <!-- File Upload Form -->
                                    <form action="<?= base_url('home/upload_bukti') ?>" method="post" enctype="multipart/form-data" style="display: inline-block;">
                                        <input type="hidden" name="kode_transaksi" value="<?= $kin->kode_transaksi ?>">
                                        <div class="form-group">
                                            <input type="file" name="bukti_file" class="form-control-file" required>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-upload"><i class="fas fa-upload"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($index === 0): ?>
                                    <!-- Only display Details and Finish Order buttons on the first row -->
                                    <button class="btn btn-danger btn-circle btn-details" data-toggle="modal" data-target="#modalDetail<?= $kode_transaksi ?>">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                    <form action="<?= base_url('home/rate_order') ?>" method="post" style="display: inline-block;">
                                        <input type="hidden" name="kode_transaksi" value="<?= $kin->kode_transaksi ?>">
                                        <input type="hidden" name="id_transaksi" value="<?= $kin->id_transaksi ?>">
                                        <div class="form-group">
                                            <select name="rating" class="form-control form-control-sm" required>
                                                <option value="">Rate</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-rate"><i class="fas fa-star"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail -->
<?php foreach($grouped_jel as $kode_transaksi => $transactions): ?>
    <div class="modal fade" id="modalDetail<?= $kode_transaksi ?>" tabindex="-1" role="dialog" aria-labelledby="modalDetailTitle<?= $kode_transaksi ?>" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDetailTitle<?= $kode_transaksi ?>">Order Details (Kode Transaksi: <?= $kode_transaksi ?>)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama User</th>
                                    <th scope="col">Nama Makanan</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Tanggal Pembelian</th>
                                    <th scope="col">Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach($transactions as $kin): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $kin->username ?></td>
                                        <td><?= $kin->nama ?></td>
                                        <td><?= number_format($kin->total_harga, 2, ',', '.') ?></td>
                                        <td><?= $kin->tgl_transaksi ?></td>
                                        <td><?= $kin->rating ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
