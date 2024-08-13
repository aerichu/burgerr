<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Menu Burger List</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fc;
        }

        .card-header {
            background-color: #4e73df;
            color: #fff;
            font-weight: bold;
        }

        .btn-success, .btn-info, .btn-outline-success, .btn-outline-danger {
            margin-right: 5px;
        }

        .table thead th {
            background-color: #4e73df;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .modal-content {
            border-radius: 8px;
        }

        .modal-header {
            border-bottom: 1px solid #e3e6f0;
        }

        .modal-footer {
            border-top: 1px solid #e3e6f0;
        }

        .modal-title {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h1 class="h3 mb-2 text-gray-800">Menu Burger List</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-white">Kalau ga beli nanti makin gay</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12 text-right">
                        <?php if(session()->get('level') == 3 || session()->get('level') == 2): ?>
                        <a href="<?= base_url('home/t_burger') ?>">
                            <button class="btn btn-success"><i class="fas fa-plus"></i> Tambah Data</button>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Nama Makanan</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Harga</th>
                                <?php if(session()->get('level') == 3 || session()->get('level') == 2): ?>
                                <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; foreach($mel as $kin): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $kin->nama ?></td>
                                <td><img src="<?= base_url('img/'.$kin->gambar) ?>" width="80px" class="img-thumbnail"></td>
                                <td><?= $kin->harga ?></td>

                                <?php if(session()->get('level') == 3 || session()->get('level') == 2): ?>
                                <td>
                                    <a href="<?= base_url('home/h_burger/'.$kin->id_makanan) ?>" class="btn btn-outline-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <a href="<?= base_url('home/e_burger/'.$kin->id_makanan) ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-success btn-sm add-to-cart" data-id="<?= $kin->id_makanan ?>" data-name="<?= $kin->nama ?>" data-toggle="modal" data-target="#addToCartModal">
                                        <i class="fa fa-shopping-cart"></i>
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

        <!-- Add to Cart Modal -->
        <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToCartModalLabel">Tambah ke Keranjang</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="<?= base_url('home/aksi_t_keranjang') ?>" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="jumlah">Jumlah:</label>
                                <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                            </div>
                            <input type="hidden" name="id_makanan" id="id_makanan">
                            <input type="hidden" name="nama_makanan" id="nama_makanan">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.add-to-cart').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#id_makanan').val(id);
                $('#nama_makanan').val(name);
                $('#addToCartModalLabel').text('Tambah ' + name + ' ke Keranjang');
            });
        });
    </script>

</body>

</html>
