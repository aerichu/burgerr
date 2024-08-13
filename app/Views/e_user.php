<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-6">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-3">Manage customers's account!</h1>
                            </div>
                            <form action="<?=base_url('home/aksi_e_user')?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                            <label for="yourUsername" class="form-label">Nama user</label>
                                            <input type="text" class="form-control input-default" placeholder="masukan nama anda" name="nama" value="<?= $php->username?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="yourUsername" class="form-label">Password</label>
                                            <input type="Password" class="form-control input-default" placeholder="masukan password baru" name="pass">
                                        </div>
                                        <div class="form-group">
                                            <div class="form-group">
                                            <label for="yourUsername" class="form-label">Jenis kelamin</label>
                                                <div class="form-group">
                                                    <select class="form-control" name="jk">
                                                        <option value="<?= $php->jk?>"><?= $php->jk?></option>
                                                        <option value="Laki-laki">Laki-laki</option>
                                                        <option value="Perempuan">Perempuan</option>
                                                        <option value="Lainnya">Lainnya</option>
                                                    </select>
                                                </div>
                                        </div>
                                        <input type="hidden" name="id" value="<?= $php->id_user?>">
                                <button class="btn login-form__btn submit w-100 btn btn-primar\">Log In</button>
                                        </a>
                                </a>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>

