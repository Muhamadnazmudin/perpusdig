<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800">Laporan Peminjaman Buku</h1>

    <!-- FILTER TANGGAL -->
    <div class="card shadow mb-4">
        <div class="card-body">

            <form method="get" class="form-inline">

                <div class="form-group mr-2">
                    <label class="mr-2">Dari</label>
                    <input type="date"
                           name="awal"
                           class="form-control"
                           value="<?= $this->input->get('awal') ?>">
                </div>

                <div class="form-group mr-2">
                    <label class="mr-2">Sampai</label>
                    <input type="date"
                           name="akhir"
                           class="form-control"
                           value="<?= $this->input->get('akhir') ?>">
                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filter
                </button>

                <a href="<?= site_url('laporan/peminjaman') ?>"
                   class="btn btn-secondary ml-2">
                    Reset
                </a>

            </form>

        </div>
    </div>

    <!-- TABEL LAPORAN -->
    <div class="card shadow">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Judul Buku</th>
                        <th>Jatuh Tempo</th>
                        <th>Kembali</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                    </tr>
                </thead>
                <tbody>

                <?php if (empty($laporan)): ?>
                    <tr>
                        <td colspan="10" class="text-center text-muted">
                            Tidak ada data
                        </td>
                    </tr>
                <?php endif; ?>

                <?php $no=1; foreach ($laporan as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal_pinjam)) ?></td>
                        <td><?= htmlspecialchars($row->nama) ?></td>
                        <td><?= htmlspecialchars($row->nis) ?></td>
                        <td><?= htmlspecialchars($row->nama_kelas ?? '-') ?></td>
                        <td><?= htmlspecialchars($row->judul) ?></td>
                        <td><?= date('d-m-Y', strtotime($row->tanggal_jatuh_tempo)) ?></td>
                        <td>
                            <?= $row->tanggal_kembali
                                ? date('d-m-Y', strtotime($row->tanggal_kembali))
                                : '-' ?>
                        </td>
                        <td>
                            <?php if ($row->status === 'dipinjam'): ?>
                                <span class="badge badge-warning">Dipinjam</span>
                            <?php else: ?>
                                <span class="badge badge-success">Kembali</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($row->hari_terlambat > 0): ?>
                                <span class="badge badge-danger">
                                    <?= $row->hari_terlambat ?> hari
                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                </tbody>
            </table>

        </div>
    </div>

</div>
