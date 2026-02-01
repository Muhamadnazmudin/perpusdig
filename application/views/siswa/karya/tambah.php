<div class="container-fluid">

    <h1 class="h5 mb-4 text-gray-800">✍️ Tambah Karya</h1>

    <form action="<?= site_url('SiswaKarya/simpan') ?>"
          method="post"
          enctype="multipart/form-data">

        <div class="card shadow-sm">
            <div class="card-body">

                <!-- JUDUL -->
                <div class="form-group">
                    <label>Judul Karya <span class="text-danger">*</span></label>
                    <input type="text"
                           name="judul"
                           class="form-control"
                           required>
                </div>

                <!-- JENIS KARYA -->
                <div class="form-group">
                    <label>Jenis Karya <span class="text-danger">*</span></label>
                    <select name="jenis" class="form-control" required>
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Komik">Komik</option>
                        <option value="Puisi">Puisi</option>
                        <option value="Cerpen">Cerpen</option>
                        <option value="Novel">Novel</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- MAPEL -->
                <div class="form-group">
                    <label>Mapel (opsional)</label>
                    <input type="text"
                           name="mapel"
                           class="form-control"
                           placeholder="Contoh: Bahasa Indonesia">
                </div>

                <!-- KELAS -->
                <div class="form-group">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="UMUM">Umum</option>
                        <option value="X">X</option>
                        <option value="XI">XI</option>
                        <option value="XII">XII</option>
                    </select>
                </div>

                <!-- FILE -->
                <div class="form-group">
                    <label>Upload File (PDF) <span class="text-danger">*</span></label>
                    <input type="file"
                           name="file_local"
                           class="form-control"
                           accept=".pdf"
                           required>
                    <small class="text-muted">
                        Maksimal 10MB, format PDF
                    </small>
                </div>

            </div>

            <div class="card-footer text-right">
                <a href="<?= site_url('SiswaKarya') ?>"
                   class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit"
                        class="btn btn-primary">
                    Kirim Karya
                </button>
            </div>
        </div>

    </form>

</div>
