<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-6">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-3">Edit your menu here!</h1>
                            </div>
                            <form action="<?=base_url('home/aksi_e_burger')?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                            <label for="yourUsername" class="form-label">Nama makanan</label>
                                            <input type="text" class="form-control input-default" placeholder="masukan nama makanan" name="nama" value="<?= $php->nama?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="yourUsername" class="form-label">Harga</label>
                                            <input type="text" class="form-control input-default" placeholder="masukan harga" name="harga" value="<?= $php->harga?>">
                                        </div>

                                        <input type="hidden" name="id" value="<?= $php->id_makanan?>">
                                <button class="btn login-form__btn submit w-100 btn btn-primar\">Submit</button>
                                        </a>
                                </a>
                                
                        </div>
                    </div>
                </div>
            </div>
        </div>

