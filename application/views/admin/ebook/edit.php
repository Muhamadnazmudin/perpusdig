<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <form action="<?= site_url('AdminEbook/update/'.$ebook->id_ebook) ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="card shadow">
            <div class="card-body">

                <!-- JUDUL -->
                <div class="form-group">
                    <label>Judul <span class="text-danger">*</span></label>
                    <input type="text"
                           name="judul"
                           value="<?= htmlspecialchars($ebook->judul) ?>"
                           class="form-control"
                           required>
                </div>

                <!-- MAPEL -->
                <div class="form-group">
                    <label>Mapel</label>
                    <input type="text"
                           name="mapel"
                           value="<?= htmlspecialchars($ebook->mapel) ?>"
                           class="form-control">
                </div>

                <!-- KELAS -->
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="X"   <?= $ebook->kelas=='X'?'selected':'' ?>>X</option>
                        <option value="XI"  <?= $ebook->kelas=='XI'?'selected':'' ?>>XI</option>
                        <option value="XII" <?= $ebook->kelas=='XII'?'selected':'' ?>>XII</option>
                    </select>
                </div>

                <!-- FILE DRIVE -->
                <div class="form-group">
                    <label>File ID Google Drive</label>
                    <input type="text"
                           name="drive_link"
                           value="<?= htmlspecialchars($ebook->file_drive) ?>"
                           class="form-control">
                    <small class="text-muted">
                        Isi FILE ID saja. Kosongkan jika tidak ingin mengubah.
                    </small>
                </div>

                <!-- COVER -->
                <div class="form-group">
                    <label>Cover E-Book</label>

                    <?php if (!empty($ebook->cover)): ?>
                        <div class="mb-2">
                            <img src="<?= base_url('assets/uploads/cover_ebook/'.$ebook->cover) ?>"
                                 alt="Cover"
                                 style="max-height:150px"
                                 class="img-thumbnail">
                        </div>
                    <?php endif; ?>

                    <input type="file"
                           name="cover"
                           class="form-control-file"
                           accept="image/*">

                    <small class="text-muted">
                        Opsional. JPG / PNG, maksimal 2MB.  
                        Jika tidak diupload, cover lama tetap.
                    </small>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="<?= site_url('AdminEbook') ?>" class="btn btn-secondary">
                    Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>

</div>
