<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Peminjaman Buku</h1>

    <a href="<?= site_url('peminjaman/tambah') ?>" class="btn btn-primary mb-3">
    Tambah Peminjaman
</a>

<?php if ($total_menunggu > 0): ?>
    <div class="alert alert-warning">
        <i class="fas fa-bell"></i>
        Ada <strong><?= $total_menunggu ?></strong> pengajuan peminjaman menunggu persetujuan.
    </div>
<?php endif; ?>

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
    <?php if ($p->status === 'menunggu'): ?>
        <span class="badge badge-warning">Menunggu</span>

    <?php elseif ($p->status === 'dipinjam'): ?>

        <?php if ($p->hari_terlambat > 0): ?>
            <span class="badge badge-danger">
                Terlambat <?= $p->hari_terlambat ?> hari
            </span>
        <?php else: ?>
            <span class="badge badge-success">Dipinjam</span>
        <?php endif; ?>

    <?php elseif ($p->status === 'ditolak'): ?>
        <span class="badge badge-danger">Ditolak</span>

    <?php elseif ($p->status === 'kembali'): ?>
        <span class="badge badge-secondary">Kembali</span>
    <?php endif; ?>
</td>
<td>
    <?php if ($p->status === 'menunggu'): ?>
        <a href="<?= site_url('peminjaman/setujui/'.$p->id_pinjam) ?>"
           class="btn btn-sm btn-success"
           onclick="return confirm('Setujui peminjaman ini?')">
           Setujui
        </a>

        <a href="<?= site_url('peminjaman/tolak/'.$p->id_pinjam) ?>"
           class="btn btn-sm btn-danger"
           onclick="return confirm('Tolak pengajuan ini?')">
           Tolak
        </a>

    <?php elseif ($p->status === 'dipinjam'): ?>
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
