<div class="section pt-4">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 fw-bold"><?= e($title) ?></h1>
                <p class="text-muted"><?= e($description) ?></p>
            </div>
        </div>

        <div class="row g-3" id="gallery-grid">
            <?php foreach ($images as $img): ?>
                <div class="col-6 col-sm-4 col-lg-3">
                    <div class="gallery-item position-relative overflow-hidden rounded-3 border shadow-sm">
                        <a href="<?= e(upload_url($img['path'])) ?>" class="gallery-link d-block" data-bs-toggle="modal" data-bs-target="#galleryModal" onclick="return openGalleryImage(this)">
                            <img src="<?= e(upload_url($img['path'])) ?>" alt="<?= e($img['title']) ?>" class="img-fluid w-100 h-100 object-fit-cover" loading="lazy" onerror="this.src='<?= e(asset('images/fallback-gallery.svg')) ?>'">
                            <div class="gallery-overlay position-absolute inset-0 bg-black bg-opacity-50 d-flex align-items-center justify-content-center opacity-0 transition-opacity duration-200">
                                <?= icon('zoom', 22) ?>
                            </div>
                        </a>
                        <?php if ($img['title']): ?>
                            <div class="p-2 small text-truncate" style="max-width: 100%"><?= e($img['title']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($images)): ?>
                <div class="col-12">
                    <p class="text-center text-muted py-5"><?= __('common.no_products') ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Gallery modal -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0">
            <div class="modal-body p-0">
                <button class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="galleryModalImage" src="" alt="" class="img-fluid w-100 h-100 object-fit-contain" style="max-height: 75vh; background: #000;">
            </div>
        </div>
    </div>
</div>

<script>
function openGalleryImage(link) {
    var img = link.querySelector('img');
    var modalImg = document.getElementById('galleryModalImage');
    if (img && modalImg) modalImg.src = img.src;
    return true;
}
</script>
