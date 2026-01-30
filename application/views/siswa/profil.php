<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card shadow text-center">
                <div class="card-header bg-primary text-white">
                    <strong>ID CARD SISWA</strong>
                </div>

                <div class="card-body">

                    <h5 class="mb-1"><?= htmlspecialchars($siswa->nama_siswa) ?></h5>
                    <small class="text-muted">Siswa</small>

                    <hr>

                    <table class="table table-sm table-borderless mb-3">
                        <tr>
                            <td align="left">NIS</td>
                            <td align="right"><strong><?= $siswa->nis ?></strong></td>
                        </tr>
                        <tr>
                            <td align="left">Kelas</td>
                            <td align="right"><?= $siswa->nama_kelas ?></td>
                        </tr>
                        <tr>
                            <td align="left">Jurusan</td>
                            <td align="right"><?= $siswa->nama_jurusan ?></td>
                        </tr>
                    </table>

                    <?php if ($siswa->qr_code): ?>
                        <img src="<?= base_url($siswa->qr_code) ?>"
                             alt="QR Code"
                             width="180">
                        <p class="mt-2 text-muted small">
                            Scan QR saat berkunjung ke perpustakaan
                        </p>
                    <?php endif; ?>

                </div>

                <div class="card-footer text-muted small">
                    Perpustakaan Digital Sekolah
                </div>
            </div>

        </div>
    </div>

</div>
