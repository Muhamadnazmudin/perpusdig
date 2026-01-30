<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif ?>

    <div class="card shadow mb-4">
        <div class="card-body">

            <!-- ================= TAMBAH SISWA ================= -->
            <form action="<?= site_url('siswa/tambah') ?>" method="post" class="mb-3">
                <input type="hidden"
                       name="<?= $this->security->get_csrf_token_name(); ?>"
                       value="<?= $this->security->get_csrf_hash(); ?>">

                <div class="form-row">
                    <div class="col-md-2 mb-2">
                        <input type="text" name="nis" class="form-control" placeholder="NIS" required>
                    </div>
                    <div class="col-md-3 mb-2">
                        <input type="text" name="nama_siswa" class="form-control" placeholder="Nama Siswa" required>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="id_kelas" class="form-control" required>
                            <option value="">- Kelas -</option>
                            <?php foreach($kelas as $k): ?>
                                <option value="<?= $k->id_kelas ?>">
                                    <?= htmlspecialchars($k->nama_kelas) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-2">
                        <select name="id_jurusan" class="form-control" required>
                            <option value="">- Jurusan -</option>
                            <?php foreach($jurusan as $j): ?>
                                <option value="<?= $j->id_jurusan ?>">
                                    <?= htmlspecialchars($j->nama_jurusan) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-1 mb-2">
                        <button class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
            </form>

            <!-- ================= IMPORT SISWA ================= -->
            <form action="<?= site_url('siswa/import') ?>" method="post" enctype="multipart/form-data" class="mb-4">
                <div class="form-row">
                    <div class="col-md-4">
                        <input type="file" name="file" class="form-control" accept=".xls,.xlsx,.csv" required>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-success">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                    </div>
                    <div class="col-md-6 text-muted">
                        Format: NIS | Nama | ID Kelas | ID Jurusan
                    </div>
                </div>
            </form>

            <!-- ================= TABLE ================= -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="40">No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Jurusan</th>
                            <th width="80">QR</th>
                            <th width="110">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no=1; foreach($siswa as $s): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($s->nis) ?></td>
                            <td><?= htmlspecialchars($s->nama_siswa) ?></td>
                            <td><?= htmlspecialchars($s->nama_kelas) ?></td>
                            <td><?= htmlspecialchars($s->nama_jurusan) ?></td>
                            <td class="text-center">
                                <?php if($s->qr_code): ?>
                                    <img src="<?= base_url($s->qr_code) ?>" width="55">
                                <?php endif ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm"
                                        data-toggle="modal"
                                        data-target="#edit<?= $s->id_siswa ?>">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <a href="<?= site_url('siswa/hapus/'.$s->id_siswa) ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Hapus siswa ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- ================= MODAL EDIT ================= -->
                        <div class="modal fade" id="edit<?= $s->id_siswa ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= site_url('siswa/edit') ?>" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Siswa</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_siswa" value="<?= $s->id_siswa ?>">

                                            <div class="form-group">
                                                <label>NIS</label>
                                                <input type="text" name="nis" class="form-control"
                                                       value="<?= $s->nis ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="nama_siswa" class="form-control"
                                                       value="<?= $s->nama_siswa ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Kelas</label>
                                                <select name="id_kelas" class="form-control">
                                                    <?php foreach($kelas as $k): ?>
                                                        <option value="<?= $k->id_kelas ?>"
                                                            <?= $k->id_kelas==$s->id_kelas?'selected':'' ?>>
                                                            <?= $k->nama_kelas ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Jurusan</label>
                                                <select name="id_jurusan" class="form-control">
                                                    <?php foreach($jurusan as $j): ?>
                                                        <option value="<?= $j->id_jurusan ?>"
                                                            <?= $j->id_jurusan==$s->id_jurusan?'selected':'' ?>>
                                                            <?= $j->nama_jurusan ?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary">Simpan</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- ================= END MODAL ================= -->

                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>
