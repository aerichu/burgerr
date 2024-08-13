<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="<?= base_url('vendor/bootstrap/css/bootstrap.min.css') ?>">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">User Profile</h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <span>User Information</span>
                        <button id="editButton" class="btn btn-sm btn-primary float-right">Edit</button>
                    </div>
                    <div class="card-body" id="profileInfo">
                        <table class="table">
                            <tr>
                                <td>Username:</td>
                                <td><?= $user->username ?></td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                                <td><?= $user->jk ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="card-body" id="editForm" style="display: none;">
                        <form action="<?= base_url('home/aksi_e_profile') ?>" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= $user->id_user ?>">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" value="<?= $user->username ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="jk">Gender</label>
                                <select name="jk" id="jk" class="form-control">
                                    <option value="Laki-laki">Male</option>
                                    <option value="Perempuan">Female</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" id="cancelButton" class="btn btn-secondary">Cancel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        document.getElementById('editButton').addEventListener('click', function() {
            document.getElementById('profileInfo').style.display = 'none';
            document.getElementById('editForm').style.display = 'block';
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none';
            document.getElementById('profileInfo').style.display = 'block';
        });

        document.getElementById('backButton').addEventListener('click', function() {
            document.getElementById('editForm').style.display = 'none';
            document.getElementById('profileInfo').style.display = 'block';
        });
    </script>
</body>
</html>
