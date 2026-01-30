<div class="container-fluid">

    <h1 class="h3 mb-4 text-gray-800"><?= $title ?></h1>

    <div class="row">

        <?php foreach ($buku as $b): ?>
        <div class="col-md-3 mb-4">
            <div class="card shadow h-100">

                <img src="<?= base_url('uploads/cover/'.$b->cover) ?>"
                     class="card-img-top"
                     style="height:200px;object-fit:cover">

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
        <?php endforeach ?>

    </div>

</div>
