<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Peminjaman Buku</h1>

    <a href="<?= site_url('peminjaman/tambah') ?>" class="btn btn-primary mb-3">
    Tambah Peminjaman
</a>


    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success">
            <?= $this->session->flashdata('success') ?>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Peminjam</th>
                        <th>Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Jatuh Tempo</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no=1; foreach ($peminjaman as $p): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= htmlspecialchars($p->nama) ?></td>
                        <td><?= htmlspecialchars($p->judul) ?></td>
                        <td><?= $p->tanggal_pinjam ?></td>
                        <td><?= $p->tanggal_jatuh_tempo ?></td>
                        <td>
<?php if ($p->status === 'kembali'): ?>
    <span class="badge badge-success">Kembali</span>

<?php elseif ($p->hari_terlambat > 0): ?>
    <span class="badge badge-danger">
        Terlambat <?= $p->hari_terlambat ?> hari
    </span>

<?php else: ?>
    <span class="badge badge-warning">Dipinjam</span>
<?php endif; ?>
</td>

                        <td>
                            <?php if ($p->status=='dipinjam'): ?>
                                <a href="<?= site_url('peminjaman/kembali/'.$p->id_pinjam) ?>"
                                   class="btn btn-sm btn-success"
                                   onclick="return confirm('Kembalikan buku ini?')">
                                   Kembalikan
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>

        </div>
    </div>

</div>
