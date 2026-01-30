<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex flex-column flex-md-row 
                justify-content-between align-items-md-center 
                gap-2 mb-3">

        <h1 class="h6 text-gray-800 mb-0">
            <i class="fas fa-book-open mr-1"></i>
            <?= htmlspecialchars($ebook->judul); ?>
        </h1>

        <div class="d-flex gap-2">
            <a href="<?= site_url('SiswaEbook/favorit/'.$ebook->id_ebook) ?>"
               class="btn btn-warning btn-sm">
                ⭐ Favorit
            </a>

            <a href="<?= site_url('SiswaEbook'); ?>"
               class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- READER -->
    <div class="card shadow-sm border-0 ebook-reader-card">
        <div class="card-body p-0">

            <iframe
                src="https://drive.google.com/file/d/<?= htmlspecialchars($ebook->file_drive); ?>/preview"
                class="ebook-iframe"
                allow="autoplay">
            </iframe>

        </div>
    </div>

</div>
<style>
/* Card reader */
.ebook-reader-card {
    border-radius: 12px;
    overflow: hidden;
}

/* Iframe responsive */
.ebook-iframe {
    width: 100%;
    height: 75vh;
    border: none;
}

/* Mobile optimization */
@media (max-width: 576px) {
    .ebook-iframe {
        height: 85vh;
    }
}
@media (max-width: 576px) {
    .ebook-header {
        position: sticky;
        top: 0;
        background: #fff;
        z-index: 10;
        padding-bottom: 8px;
    }
}

</style>
