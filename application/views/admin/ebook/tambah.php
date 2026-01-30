<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <form action="<?= site_url('AdminEbook/simpan') ?>" method="post">

        <div class="card shadow">
            <div class="card-body">

                <!-- JUDUL -->
                <div class="form-group">
                    <label>Judul <span class="text-danger">*</span></label>
                    <input type="text"
                           name="judul"
                           class="form-control"
                           required>
                </div>

                <!-- MAPEL -->
                <div class="form-group">
                    <label>Mapel</label>
                    <input type="text"
                           name="mapel"
                           class="form-control">
                </div>

                <!-- KELAS -->
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>

                <!-- FILE DRIVE -->
                <div class="form-group">
                    <label>File ID Google Drive <span class="text-danger">*</span></label>
                    <input type="text"
                           name="drive_link"
                           class="form-control"
                           required>
                    <small class="text-muted">
                        Contoh: <code>1mwQ6aaFLE1Oxg7iO6op7Oplu7ZFcGaYi</code><br>
                        (tidak perlu https://)
                    </small>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="<?= site_url('AdminEbook') ?>" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </div>

    </form>

</div>
