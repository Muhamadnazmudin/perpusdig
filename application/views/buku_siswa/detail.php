<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Detail Buku</h1>

    <div class="card shadow">
        <div class="card-body row">

            <div class="col-md-4">
                <img src="<?= base_url('uploads/cover/'.$buku->cover) ?>"
                     class="img-fluid">
            </div>
                   
            <div class="col-md-8">
                 <small class="text-muted">
    Maksimal <?= $this->config->item('max_pinjam') ?> buku aktif
</small>
                <h4><?= htmlspecialchars($buku->judul) ?></h4>

                <table class="table table-sm">
                    <tr><th>Penulis</th><td><?= htmlspecialchars($buku->penulis) ?></td></tr>
                    <tr><th>Penerbit</th><td><?= htmlspecialchars($buku->penerbit) ?></td></tr>
                    <tr><th>Tahun</th><td><?= $buku->tahun ?></td></tr>
                    <tr><th>Stok</th><td><?= $buku->stok ?></td></tr>
                </table>

                <?php if ($buku->stok > 0): ?>
                    <a href="<?= site_url('peminjaman/pinjam/'.$buku->id_buku) ?>"
                       class="btn btn-success"
                       onclick="return confirm('Pinjam buku ini?')">
                       Pinjam Buku
                    </a>
                <?php else: ?>
                    <button class="btn btn-secondary" disabled>Stok Habis</button>
                <?php endif; ?>

                <a href="<?= site_url('buku') ?>" class="btn btn-light">
                    Kembali
                </a>
            </div>

        </div>
    </div>

</div>
