<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Master Kategori</h1>

    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-body">

                    <?= form_open('kategori/simpan') ?>

                        <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" required>
                        </div>

                        <button class="btn btn-primary">Simpan</button>

                    <?= form_close() ?>

                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                        </tr>
                        <?php $no=1; foreach ($kategori as $k): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($k->nama_kategori) ?></td>
                        </tr>
                        <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
