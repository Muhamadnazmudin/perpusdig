<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Data Buku Fisik</h1>
<a href="<?= site_url('buku-fisik/tambah') ?>" class="btn btn-primary btn-sm">
    <i class="fas fa-plus"></i> Tambah Buku
</a>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Daftar Buku Fisik
            </h6>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <?php if ($this->session->flashdata('success')): ?>
<div class="alert alert-success">
    <?= $this->session->flashdata('success') ?>
</div>
<?php endif; ?>

                <table class="table table-bordered">
                    <thead class="thead-light">
    <tr>
        <th>No</th>
        <th>Cover</th>
        <th>Judul</th>
        <th>Penulis</th>
        <th>Kategori</th>
        <th>Rak</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
</thead>

                    <tbody>
                        <?php if (empty($buku)): ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    Data buku belum tersedia
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no=1; foreach ($buku as $row): ?>
                            <tr>
    <td><?= $no++ ?></td>

    <!-- COVER -->
    <td class="text-center">
        <?php if (!empty($row->cover)): ?>
            <img src="<?= base_url('uploads/cover/'.$row->cover) ?>"
                 alt="Cover"
                 style="width:60px; height:auto;"
                 class="img-thumbnail">
        <?php else: ?>
            <img src="<?= base_url('assets/sbadmin2/img/undraw_book.svg') ?>"
                 alt="No Cover"
                 style="width:60px; height:auto;"
                 class="img-thumbnail">
        <?php endif; ?>
    </td>

    <td><?= htmlspecialchars($row->judul) ?></td>
    <td><?= htmlspecialchars($row->penulis) ?></td>
    <td><?= $row->nama_kategori ?? '-' ?></td>
    <td><?= $row->kode_rak ?? '-' ?></td>
    <td><?= $row->stok ?></td>

    <td class="text-center">
        <a href="<?= site_url('buku-fisik/detail/'.$row->id_buku) ?>"
           class="btn btn-sm btn-info">
            Detail
        </a>

        <a href="<?= site_url('buku-fisik/edit/'.$row->id_buku) ?>"
           class="btn btn-sm btn-warning">
            Edit
        </a>

        <a href="<?= site_url('buku-fisik/hapus/'.$row->id_buku) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Yakin ingin menghapus buku ini?')">
            Hapus
        </a>
    </td>
</tr>

                            <?php endforeach ?>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
