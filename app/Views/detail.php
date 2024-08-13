<div class="content-body">
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-12 col-lg-12 col-sm-12 col-xxl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Profile details <?= $php->username?></h4>
                        <div id="activity">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">
                              <p class="small fst-italic"></p>

                              <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Nama Lengkap</div>
                                <div class="col-lg-19 col-md-19"><?= $php->username?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Jenis kelamin</div>
                                <div class="col-lg-19 col-md-19"><?= $php->jk?></div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">password</div>
                                <div class="col-lg-19 col-md-19"><?= $php->pw?></div>
                            </div>
                          </div>

                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>
</div>
</div>
