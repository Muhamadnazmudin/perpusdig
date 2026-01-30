<div class="container-fluid">

    <h1 class="h4 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">

        <!-- ================= SCAN CAMERA ================= -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    Scan QR via Kamera
                </div>
                <div class="card-body text-center">

                    <div id="reader" style="width:100%;"></div>

                    <div id="notif" class="mt-3"></div>

                    <small class="text-muted d-block mt-2">
                        Arahkan kamera ke QR Code siswa
                    </small>

                </div>
            </div>
        </div>

        <!-- ================= DAFTAR HARI INI ================= -->
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header">
                    Kunjungan Hari Ini
                </div>
                <div class="card-body table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Tujuan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($kunjungan as $k): ?>
                            <tr>
                                <td><?= $k->jam ?></td>
                                <td><?= $k->nis ?></td>
                                <td><?= $k->nama_siswa ?></td>
                                <td><?= $k->nama_kelas ?></td>
                                <td><?= $k->nama_jurusan ?></td>
                                <td><?= $k->tujuan ?></td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

<!-- ================= MODAL KONFIRMASI ================= -->
<div class="modal fade" id="modalKunjungan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Konfirmasi Kunjungan</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <form id="formKunjungan">

          <input type="hidden" name="id_siswa" id="id_siswa">
          <input type="hidden" name="jam" id="jam">

          <div class="form-group">
            <label>NIS</label>
            <input type="text" id="nis" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Nama</label>
            <input type="text" id="nama" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Kelas</label>
            <input type="text" id="kelas" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Jurusan</label>
            <input type="text" id="jurusan" class="form-control" readonly>
          </div>

          <div class="form-group">
            <label>Tujuan Kunjungan</label>
            <select name="tujuan" class="form-control" required>
              <option value="">- Pilih Tujuan -</option>
              <option value="Pinjam Buku">Pinjam Buku</option>
              <option value="Baca di Tempat">Baca di Tempat</option>
              <option value="Mengembalikan Buku">Mengembalikan Buku</option>
              <option value="Lainnya">Lainnya</option>
            </select>
          </div>

        </form>

        <div id="modalNotif"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-primary" id="btnSimpan">
            <i class="fas fa-save"></i> Simpan Kunjungan
        </button>
      </div>

    </div>
  </div>
</div>

<!-- ================= QR SCANNER ================= -->
<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let lastScan = '';

function onScanSuccess(decodedText) {

    if (decodedText === lastScan) return;
    lastScan = decodedText;

    fetch('<?= site_url('kunjungan/scan') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'token=' + encodeURIComponent(decodedText)
    })
    .then(res => res.json())
    .then(res => {

        if (!res.status) {
            lastScan = '';
            document.getElementById('notif').innerHTML =
                `<div class="alert alert-danger">QR tidak valid</div>`;
            return;
        }

        // isi modal
        document.getElementById('id_siswa').value = res.siswa.id_siswa;
        document.getElementById('nis').value      = res.siswa.nis;
        document.getElementById('nama').value     = res.siswa.nama_siswa;
        document.getElementById('kelas').value    = res.siswa.nama_kelas;
        document.getElementById('jurusan').value  = res.siswa.nama_jurusan;
        document.getElementById('jam').value      = res.jam;

        document.getElementById('modalNotif').innerHTML = '';
        $('#modalKunjungan').modal('show');
    });
}

// simpan dari modal
document.getElementById('btnSimpan').addEventListener('click', function () {

    let form = new FormData(document.getElementById('formKunjungan'));

    fetch('<?= site_url('kunjungan/simpan') ?>', {
        method: 'POST',
        body: form
    })
    .then(res => res.json())
    .then(res => {

        document.getElementById('modalNotif').innerHTML =
            `<div class="alert ${res.status ? 'alert-success' : 'alert-danger'}">
                ${res.msg}
            </div>`;

        if (res.status) {
            setTimeout(() => location.reload(), 800);
        }
    });
});

// init camera
const html5QrCode = new Html5Qrcode("reader");

Html5Qrcode.getCameras().then(cameras => {
    if (cameras.length) {
        html5QrCode.start(
            cameras[0].id,
            { fps: 10, qrbox: { width: 220, height: 220 } },
            onScanSuccess
        );
    }
}).catch(() => {
    document.getElementById('notif').innerHTML =
        '<div class="alert alert-danger">Kamera tidak tersedia</div>';
});
</script>
