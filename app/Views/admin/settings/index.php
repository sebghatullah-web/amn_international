<?php
$tabs = [
    'general'  => __('admin.company'),
    'contact'  => __('admin.contact_tab'),
    'home'     => __('admin.home_tab'),
    'currency' => __('admin.currency_tab'),
    'seo'      => __('admin.seo'),
    'footer'   => __('admin.footer_tab'),
];
$s = $settings;
$val = function (string $key, string $default = '') use ($s) { return e($s[$key] ?? $default); };
?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent border-0">
        <h5 class="fw-bold mb-0"><?= __('admin.settings') ?></h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills mb-4 flex-wrap gap-2" role="tablist">
            <?php foreach ($tabs as $key => $label): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link<?= $tab === $key ? ' active' : '' ?>" id="tab-<?= $key ?>" data-bs-toggle="pill" data-bs-target="#panel-<?= $key ?>" type="button" role="tab" aria-controls="panel-<?= $key ?>" aria-selected="<?= $tab === $key ? 'true' : 'false' ?>">
                        <?= e($label) ?>
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content">
                <div class="tab-pane fade<?= $tab === 'general' ? ' show active' : '' ?>" id="panel-general" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/general')) ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><?= __('admin.company') ?></label>
                                <input type="text" name="company_name" class="form-control" required value="<?= $val('company_name', 'AMN International') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('admin.general') ?></label>
                                <input type="text" name="company_slogan" class="form-control" value="<?= $val('company_slogan') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.email') ?></label>
                                <input type="email" name="admin_email" class="form-control" value="<?= $val('admin_email') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Site language</label>
                                <input type="text" name="site_language" class="form-control" value="<?= $val('site_language', 'en') ?>" disabled>
                                <div class="form-text"><?= __('admin.lang_note') ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('admin.image') ?> (logo)</label>
                                <input type="file" name="company_logo" class="form-control" accept="image/*">
                                <?php if (!empty($s['company_logo'])): ?><img src="<?= e(upload_url($s['company_logo'])) ?>" class="img-thumbnail mt-2" style="max-width: 120px;"><?php endif; ?>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
                <div class="tab-pane fade<?= $tab === 'contact' ? ' show active' : '' ?>" id="panel-contact" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/contact')) ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.phone') ?></label>
                                <input type="text" name="phone" class="form-control" value="<?= $val('phone') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.whatsapp') ?></label>
                                <input type="text" name="whatsapp" class="form-control" value="<?= $val('whatsapp') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.email') ?></label>
                                <input type="email" name="email" class="form-control" value="<?= $val('email') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.address') ?></label>
                                <input type="text" name="office_address" class="form-control" value="<?= $val('office_address') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('contact.hours') ?></label>
                                <input type="text" name="working_hours" class="form-control" value="<?= $val('working_hours') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">WhatsApp link</label>
                                <input type="text" name="whatsapp_link" class="form-control" value="<?= $val('whatsapp_link') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Facebook</label>
                                <input type="text" name="social_facebook" class="form-control" value="<?= $val('social_facebook') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Instagram</label>
                                <input type="text" name="social_instagram" class="form-control" value="<?= $val('social_instagram') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">LinkedIn</label>
                                <input type="text" name="social_linkedin" class="form-control" value="<?= $val('social_linkedin') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">YouTube</label>
                                <input type="text" name="social_youtube" class="form-control" value="<?= $val('social_youtube') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">X / Twitter</label>
                                <input type="text" name="social_x" class="form-control" value="<?= $val('social_x') ?>">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
                <div class="tab-pane fade<?= $tab === 'currency' ? ' show active' : '' ?>" id="panel-currency" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/currency')) ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label"><?= __('admin.exchange_rate') ?></label>
                                <input type="text" name="usd_afn_rate" class="form-control" required value="<?= $val('usd_afn_rate', '0') ?>">
                                <div class="form-text"><?= __('admin.rate_hint') ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?= __('admin.rate_updated') ?></label>
                                <input type="text" class="form-control" value="<?= e(format_date($s['usd_afn_updated_at'] ?? '', 'Y-m-d H:i')) ?>" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">USD code</label>
                                <input type="text" name="currency_usd_code" class="form-control" value="<?= $val('currency_usd_code', 'USD') ?>">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">AFN code</label>
                                <input type="text" name="currency_afn_code" class="form-control" value="<?= $val('currency_afn_code', 'AFN') ?>">
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
                <div class="tab-pane fade<?= $tab === 'home' ? ' show active' : '' ?>" id="panel-home" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/home')) ?>" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <h6 class="fw-bold border-bottom pb-2 mb-3"><?= __('admin.home_tab') ?> — Hero</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label"><?= __('home.eyebrow') ?></label>
                                <input type="text" name="hero_eyebrow" class="form-control" value="<?= $val('hero_eyebrow') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('home.title') ?></label>
                                <input type="text" name="hero_title" class="form-control" value="<?= $val('hero_title') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><?= __('home.description') ?></label>
                                <textarea name="hero_description" class="form-control" rows="2"><?= e($s['hero_description'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"><?= __('admin.image') ?> (hero)</label>
                                <input type="file" name="hero_image" class="form-control" accept="image/*">
                                <?php if (!empty($s['hero_image'])): ?><img src="<?= e(upload_url($s['hero_image'])) ?>" class="img-thumbnail mt-2" style="max-width: 160px;"><?php endif; ?>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Hero alt text</label>
                                <input type="text" name="hero_alt" class="form-control" value="<?= $val('hero_alt') ?>">
                            </div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">CTA banner</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label"><?= __('home.cta_title') ?></label>
                                <input type="text" name="cta_title" class="form-control" value="<?= $val('cta_title') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label"><?= __('home.cta_text') ?></label>
                                <textarea name="cta_text" class="form-control" rows="2"><?= e($s['cta_text'] ?? '') ?></textarea>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label"><?= __('admin.image') ?> (CTA)</label>
                                <input type="file" name="cta_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">CTA alt</label>
                                <input type="text" name="cta_alt" class="form-control" value="<?= $val('cta_alt') ?>">
                            </div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Sections</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6"><label class="form-label"><?= __('home.categories_title') ?></label><input type="text" name="categories_title" class="form-control" value="<?= $val('categories_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label"><?= __('home.featured_title') ?></label><input type="text" name="featured_title" class="form-control" value="<?= $val('featured_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label"><?= __('home.new_title') ?></label><input type="text" name="new_title" class="form-control" value="<?= $val('new_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label"><?= __('home.popular_title') ?></label><input type="text" name="popular_title" class="form-control" value="<?= $val('popular_title') ?>"></div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Stats</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-3"><label class="form-label">Years</label><input type="text" name="stats_years" class="form-control" value="<?= $val('stats_years') ?>"></div>
                            <div class="col-md-3"><label class="form-label">Clients</label><input type="text" name="stats_clients" class="form-control" value="<?= $val('stats_clients') ?>"></div>
                            <div class="col-md-3"><label class="form-label">Shipments</label><input type="text" name="stats_shipments" class="form-control" value="<?= $val('stats_shipments') ?>"></div>
                            <div class="col-md-3"><label class="form-label">Countries</label><input type="text" name="stats_countries" class="form-control" value="<?= $val('stats_countries') ?>"></div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Announcement bar</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-4"><label class="form-label">Item 1</label><input type="text" name="announcement_1" class="form-control" value="<?= $val('announcement_1') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Item 2</label><input type="text" name="announcement_2" class="form-control" value="<?= $val('announcement_2') ?>"></div>
                            <div class="col-md-4"><label class="form-label">Item 3</label><input type="text" name="announcement_3" class="form-control" value="<?= $val('announcement_3') ?>"></div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Homepage service strip</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6"><label class="form-label">Service 1 title</label><input type="text" name="service_1_title" class="form-control" value="<?= $val('service_1_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Service 1 text</label><input type="text" name="service_1_text" class="form-control" value="<?= $val('service_1_text') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Service 2 title</label><input type="text" name="service_2_title" class="form-control" value="<?= $val('service_2_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Service 2 text</label><input type="text" name="service_2_text" class="form-control" value="<?= $val('service_2_text') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Service 3 title</label><input type="text" name="service_3_title" class="form-control" value="<?= $val('service_3_title') ?>"></div>
                            <div class="col-md-6"><label class="form-label">Service 3 text</label><input type="text" name="service_3_text" class="form-control" value="<?= $val('service_3_text') ?>"></div>
                        </div>
                        <h6 class="fw-bold border-bottom pb-2 mb-3">Footer / newsletter text</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-8"><label class="form-label"><?= __('footer.about_title') ?></label><textarea name="footer_about" class="form-control" rows="2"><?= e($s['footer_about'] ?? '') ?></textarea></div>
                            <div class="col-md-4"><label class="form-label"><?= __('footer.newsletter_text') ?></label><input type="text" name="newsletter_text" class="form-control" value="<?= $val('newsletter_text') ?>"></div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
                <div class="tab-pane fade<?= $tab === 'seo' ? ' show active' : '' ?>" id="panel-seo" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/seo')) ?>">
                        <?= csrf_field() ?>
                        <div class="row g-3 mb-4">
                            <?php
                            $seoFields = [
                                'seo_home_title' => 'Home title', 'seo_home_description' => 'Home description',
                                'seo_about_title' => 'About title', 'seo_about_description' => 'About description',
                                'seo_products_title' => 'Products title', 'seo_products_description' => 'Products description',
                                'seo_services_title' => 'Services title', 'seo_services_description' => 'Services description',
                                'seo_process_title' => 'Import process title', 'seo_process_description' => 'Import process description',
                                'seo_gallery_title' => 'Gallery title', 'seo_gallery_description' => 'Gallery description',
                                'seo_news_title' => 'News title', 'seo_news_description' => 'News description',
                                'seo_contact_title' => 'Contact title', 'seo_contact_description' => 'Contact description',
                                'seo_quote_title' => 'Quote title', 'seo_quote_description' => 'Quote description',
                            ];
                            foreach ($seoFields as $k => $lbl): ?>
                                <div class="col-md-6">
                                    <label class="form-label"><?= e($lbl) ?></label>
                                    <input type="text" name="<?= e($k) ?>" class="form-control" value="<?= $val($k) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
                <div class="tab-pane fade<?= $tab === 'footer' ? ' show active' : '' ?>" id="panel-footer" role="tabpanel">
                    <form method="post" action="<?= e(url('admin/settings/footer')) ?>">
                        <?= csrf_field() ?>
                        <div class="mb-3">
                            <label class="form-label"><?= __('footer.quick_links') ?></label>
                            <textarea name="nav_links" class="form-control font-monospace" rows="10"><?= e($s['nav_links'] ?? '') ?></textarea>
                            <div class="form-text">One link per line, format: <code>Label|/path</code>. This list drives the main menu and the quick links column.</div>
                        </div>
                        <button class="btn btn-primary" type="submit"><?= __('admin.save') ?></button>
                    </form>
                </div>
        </div>
    </div>
</div>
