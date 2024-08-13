<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .nota {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
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
        .table thead th {
            background-color: #4e73df;
            color: #fff;
        }
        .btn-status {
            display: block;
            margin: auto;
        }
        .filter-form {
            margin-bottom: 20px;
        }
        .filter-form {
            max-width: 300px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            font-size: 14px;
            padding: 6px 12px;
        }

        .btn-primary {
            font-size: 14px;
            padding: 6px 12px;
        }

    </style>
</head>
<body>
    <div class="container-fluid">
        <h1 class="nota">Pesanan</h1>
        <div class="container">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">All of the orders</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama User</th>
                                    <th>Kode Transaksi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                    <th>Makanan</th>
                                    <th>Status</th>
                                    <th>Nota Bukti</th>
                                    <?php if (session()->get('level') == 3): ?>
                                    <th>Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="orderTableBody">
                            <?php 
                            $groupedTransactions = [];
                            foreach ($jel as $transaction) {
                                $groupedTransactions[$transaction->kode_transaksi][] = $transaction;
                            }

                            $no = 1;
                            foreach ($groupedTransactions as $kodeTransaksi => $transactions): 
                            $firstTransaction = $transactions[0]; // Use the first transaction for general details
                            ?>
                            <tr class="order-row" data-status="<?= $firstTransaction->status ?>">
                                <td><?= $no++ ?></td>
                                <td><?= $firstTransaction->username ?></td>
                                <td><?= $kodeTransaksi ?></td>
                                <td><?= $firstTransaction->tgl_transaksi ?></td>
                                <td>
                                    <?php
                                    $quantities = array_map(function($transaction) {
                                        return explode(', ', $transaction->jumlah);
                                    }, $transactions);
                                    foreach (array_unique(array_merge(...$quantities)) as $quantity) {
                                        echo $quantity . '<br>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $totalHarga = array_sum(array_map(function($transaction) {
                                        return $transaction->total_harga;
                                    }, $transactions));
                                    echo $totalHarga;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $foods = array_map(function($transaction) {
                                        return explode(', ', $transaction->nama);
                                    }, $transactions);
                                    foreach (array_unique(array_merge(...$foods)) as $food) {
                                        echo $food . '<br>';
                                    }
                                    ?>
                                </td>
                                <td class="status"><?= $firstTransaction->status ?></td>
                                <td>
    <?php 
    $filePaths = explode(',', $transaction->bukti_file);
    foreach ($filePaths as $filePath) {
        if ($filePath) {
            echo '<a href="' . base_url('uploads/' . $filePath) . '" target="_blank">View File</a><br>';
        }
    }
    ?>
</td>


                                <?php if (session()->get('level') == 3): ?>
                                <td>
                                    <button class="btn btn-outline-danger btn-sm btn-status" data-kode-transaksi="<?= $kodeTransaksi ?>">
                                        Update Status
                                    </button>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            </div>
        </div>
    </div>
</div>
</div>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Nota Bukti</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="imagePreview" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Modal for Status Update -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="statusForm">
                    <div class="form-group">
                        <label for="statusSelect">Status</label>
                        <select class="form-control" id="statusSelect" name="status">
                            <option value="READY">READY</option>
                            <option value="WAITLIST">WAITLIST</option>
                            <option value="unprepared">UNPREPARED</option>
                            <option value="confirmed">CONFIRMED</option>
                        </select>
                    </div>
                    <input type="hidden" id="kodeTransaksi" name="kode_transaksi">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-status').click(function() {
            var kodeTransaksi = $(this).data('kode-transaksi');
            $('#kodeTransaksi').val(kodeTransaksi);
            $('#statusModal').modal('show');
        });

        $('#statusForm').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: '<?= base_url('home/aksi_e_pesanan') ?>',
                type: 'POST',
                data: formData,
                success: function(response) {
                    location.reload();
                }
            });
        });

        $('#filterForm').submit(function(event) {
            event.preventDefault();
            var selectedStatus = $('#statusFilter').val().toUpperCase();
            $('.order-row').each(function() {
                var rowStatus = $(this).data('status').toUpperCase();
                if (selectedStatus === '' || selectedStatus === rowStatus) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Image preview in modal
        $('#imageModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var imageUrl = button.data('src');
            var modal = $(this);
            modal.find('#imagePreview').attr('src', imageUrl);
        });
    });
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
