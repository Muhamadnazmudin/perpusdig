<style>
.cover-click:hover {
    opacity: 0.85;
    transition: 0.2s;
}
</style>

<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">

        <?php foreach ($buku as $b): ?>
        <div class="col-md-3 mb-4">

            <div class="card shadow h-100">

                <!-- COVER (CLICKABLE) -->
                <img src="<?= base_url('uploads/cover/'.$b->cover) ?>"
                     class="card-img-top cover-click"
                     data-toggle="modal"
                     data-target="#cover<?= $b->id_buku ?>"
                     style="height:200px;object-fit:cover;cursor:pointer">

                <div class="card-body">
                    <h6 class="card-title font-weight-bold">
                        <?= htmlspecialchars($b->judul) ?>
                    </h6>

                    <p class="mb-1">
                        <small><?= htmlspecialchars($b->penulis) ?></small>
                    </p>

                    <span class="badge badge-<?= $b->stok > 0 ? 'success':'danger' ?>">
                        <?= $b->stok > 0 ? 'Tersedia':'Habis' ?>
                    </span>
                </div>

                <div class="card-footer text-center">
                    <a href="<?= site_url('buku/detail/'.$b->id_buku) ?>"
                       class="btn btn-sm btn-primary">
                        Detail
                    </a>
                </div>

            </div>
        </div>

        <!-- ================= MODAL FULL COVER ================= -->
        <div class="modal fade" id="cover<?= $b->id_buku ?>" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content bg-dark">

                    <div class="modal-header border-0">
                        <h6 class="modal-title text-white">
                            <?= htmlspecialchars($b->judul) ?>
                        </h6>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body text-center p-0">
                        <img src="<?= base_url('uploads/cover/'.$b->cover) ?>"
                             class="img-fluid"
                             style="max-height:80vh;">
                    </div>

                </div>
            </div>
        </div>
        <!-- ================= END MODAL ================= -->

        <?php endforeach ?>

    </div>
</div>
