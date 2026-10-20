-- ============================================================
-- AMN International - Seed Data
-- Run after: mysql -u root -p < database/schema.sql
-- ============================================================

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `is_active`, `is_banned`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@amn-international.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, 0, NULL, NOW(), NOW());

-- ============================================================
-- Categories
-- ============================================================
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Anti-Static Bags', 'anti-static-bags', 'ESD-protective packaging for sensitive electronic components during storage and transit.', NULL, 1, 1, NOW(), NOW()),
(2, 'Anti-Static Strapping', 'anti-static-strapping', 'High-strength strapping with built-in ESD properties for sealing ESD bags and palletized loads.', NULL, 1, 2, NOW(), NOW()),
(3, 'Floor Mats & Footwear', 'floor-mats-footwear', 'Conductive floor mats and ESD footwear to keep operators grounded in ESD-protected areas.', NULL, 1, 3, NOW(), NOW()),
(4, 'Ionizers & Equipment', 'ionizers-equipment', 'Ionization guns, bars and overhead systems that neutralize electrostatic charge in workspaces.', NULL, 1, 4, NOW(), NOW()),
(5, 'Monitoring & Testing', 'monitoring-testing', 'Continuous ESD monitoring devices, test equipment and compliance labels.', NULL, 1, 5, NOW(), NOW());

-- ============================================================
-- Products
-- ============================================================
INSERT INTO `products` (`id`, `name`, `slug`, `category_id`, `short_description`, `description`, `country_of_origin`, `price_usd`, `availability_status`, `is_active`, `is_featured`, `is_new`, `is_popular`, `is_price_published`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
(1, 'ESD Anti-Static Poly Bag (Large, 12x15 inch)', 'esd-anti-static-poly-bag-large', 1, '6 mil ESD shielding poly bag for component protection.', 'Heavy-duty 6 mil electrostatic shielding poly bags with conductive liner, designed for safe storage and transport of electronic components, ICs, and PCB assemblies.', 'China', 18.50, 'in_stock', 1, 1, 1, 0, 1, 'Anti-Static Poly Bag Large | AMN International', 'ESD-certified poly bags for safe storage and transport of electronic components.', NOW(), NOW()),
(2, 'Conducting ESD Strapping (2 inch x 500 ft roll)', 'conducting-esd-strapping', 2, 'Self-adhesive ESD tape for bag sealing and packaging.', 'High-tenacity ESD strapping tape that provides continuous conductivity for sealing anti-static bags and securing palletized loads.', 'USA', 42.00, 'in_stock', 1, 0, 0, 1, 1, 'Conductive ESD Strapping Roll | AMN International', 'High-tenacity ESD strapping for secure, static-safe packaging.', NOW(), NOW()),
(3, 'Disposable ESD Wrist Strap (pack of 12)', 'disposable-esd-wrist-strap-pack-12', 3, 'Pre-assembled wrist straps with coiled cord and snap.', 'A pack of 12 lightweight, comfortable wrist straps with coiled cord and snap button for personnel grounding in ESD-protected areas.', 'China', 36.00, 'in_stock', 1, 0, 0, 0, 'ESD Wrist Strap Pack | AMN International', 'Individual wrist straps with coiled cord for personnel grounding.', NOW(), NOW()),
(4, 'Static Meter (Surface / Point Tester)', 'static-meter-surface', 5, 'Handheld instrument for checking surface charge.', 'Portable static field meter for measuring surface charge and detecting ESD risks on work surfaces, packaging, and components.', 'USA', 189.00, 'in_stock', 1, 1, 0, 0, 0, 'Static Meter Surface Tester | AMN International', 'Precision static charge measurement tool for QC and compliance.', NOW(), NOW()),
(5, 'ESD Footwear Checker / Mat Tester', 'esd-footwear-checker', 5, 'Verifies the effectiveness of ESD footwear and floor mats.', 'A portable tester for verifying the resistance of ESD footwear and floor mats, ensuring personnel grounding is within acceptable limits.', 'Germany', 249.00, 'in_stock', 1, 0, 1, 0, 0, 'ESD Footwear & Mat Tester | AMN International', 'Portable verification tool for ESD footwear and flooring.', NOW(), NOW());

-- ============================================================
-- Product image gallery (placeholder assets)
-- ============================================================
INSERT INTO `product_images` (`product_id`, `path`, `alt`, `is_primary`, `created_at`) VALUES
(1, 'uploads/products/20261018-1a2b3c4d.jpg', 'ESD Anti-Static Poly Bag Large', 1, NOW()),
(2, 'uploads/products/20261018-5e6f7g8h.jpg', 'Conducting ESD Strapping Roll', 1, NOW()),
(3, 'uploads/products/20261018-9i0j1k2l.jpg', 'Disposable ESD Wrist Strap Pack', 1, NOW()),
(4, 'uploads/products/20261018-3m4n5o6p.jpg', 'Static Meter Surface Tester', 1, NOW()),
(5, 'uploads/products/20261018-7q8r9s0t.jpg', 'ESD Footwear Checker Mat Tester', 1, NOW());

-- ============================================================
-- Product technical specifications
-- ============================================================
-- ============================================================
-- Services
-- ============================================================
INSERT INTO `services` (`name`, `slug`, `description`, `image`, `icon`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('ESD Consultancy', 'esd-consultancy', 'We assess your facility and provide a tailored ESD control program.', NULL, 'shield', 1, 1, NOW(), NOW()),
('Product Sourcing', 'product-sourcing', 'We help you identify and qualify approved ESD products from global suppliers.', NULL, 'box', 1, 2, NOW(), NOW()),
('Training', 'training', 'Hands-on workshops for your team on ESD fundamentals and best practices.', NULL, 'headset', 1, 3, NOW(), NOW()),
('Compliance Audits', 'compliance-audits', 'Third-party verification that your ESD-protected areas meet industry standards.', NULL, 'cert', 1, 4, NOW(), NOW());

-- ============================================================
-- Import process steps
-- ============================================================
INSERT INTO `process_steps` (`title`, `description`, `icon`, `image`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Request a Quote', 'Submit product specifications, quantities, and delivery requirements. We will return a detailed quotation within 24 business hours.', 'quote', NULL, 1, 1, NOW(), NOW()),
('Sourcing & Validation', 'Our engineers review your requirements, source equivalent products, and confirm specifications and compliance documentation.', 'doc', NULL, 1, 2, NOW(), NOW()),
('Production & QC', 'Products are sourced, inspected, and quality-controlled against ESD standards before packaging.', 'check', NULL, 1, 3, NOW(), NOW()),
('Global Delivery', 'We ship ESD-compliant packaging worldwide with tracking, documentation, and customs support.', 'truck', NULL, 1, 4, NOW(), NOW());

-- ============================================================
-- Gallery images
-- ============================================================
INSERT INTO `gallery_images` (`title`, `slug`, `description`, `image`, `group_name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('ESD Packaging Process', 'esd-packaging-process', 'How we package sensitive electronics for safe global delivery.', 'uploads/products/20261018-1a2b3c4d.jpg', 'Packaging', 1, 1, NOW(), NOW()),
('ESD Workstation', 'esd-workstation', 'A typical ESD-protected workstation with matting, grounding, and equipment.', 'uploads/products/20261018-5e6f7g8h.jpg', 'Workstations', 1, 2, NOW(), NOW());

-- ============================================================
-- News / Articles
-- ============================================================
INSERT INTO `articles` (`title`, `slug`, `excerpt`, `image`, `author`, `category`, `is_published`, `published_at`, `seo_title`, `seo_description`, `created_at`, `updated_at`) VALUES
('Getting Started with ESD Control: A Practical Guide', 'getting-started-esd-control', 'Learn the five essential steps to build an electrostatic discharge control program.', 'assets/images/hero-fallback.svg', 'AMN International', 'Industry', 1, NOW(), 'ESD Control Guide', 'A practical guide to building an effective electrostatic discharge control program.', NOW(), NOW());

-- ============================================================
-- Settings (key/value store)
-- ============================================================
INSERT INTO `settings` (`skey`, `svalue`) VALUES
('company_name', 'AMN International'),
('company_slogan', 'Electronic Safety Solutions for a Static-Free World'),
('site_language', 'en'),
('admin_email', 'admin@amn-international.com'),
('phone', '+93 (0) 700 000 000'),
('whatsapp', '+93 700 000 000'),
('email', 'info@amn-international.com'),
('office_address', 'Plot 121, Street 15, Diplomatic Area, Kabul, Afghanistan'),
('working_hours', 'Mon - Fri: 08:00 - 17:00 (Afghanistan Time)'),
('hero_title', 'Protecting Electronics from Static Damage'),
('hero_subtitle', 'AMN International is a Trusted Distributor of High-Quality ESD Products and Solutions'),
('hero_cta', 'Request a Quote'),
('company_logo', 'uploads/site/amn-logo.svg'),
('cta_image', 'uploads/site/cta-fallback.svg'),
('announcement_1', 'World Class ESD Products and Solutions'),
('announcement_2', 'ESD Floor Mats, Footwear, and Equipment'),
('announcement_3', 'Global Distribution - Free Consultation'),
('newsletter_text', 'Stay informed. Subscribe to our newsletter for the latest ESD news and product updates.'),
('show_demo_disclaimer', 'false'),
('currency_usd_code', 'USD'),
('currency_afn_code', 'AFN'),
('usd_afn_rate', '7045.5000'),
('usd_afn_updated_at', '2026-10-18 00:00:00'),
('seo_home_title', 'AMN International - Security Solutions'),
('seo_home_description', 'Trusted distributor of electronic safety products in Afghanistan and worldwide.'),
('seo_about_title', 'About AMN International'),
('seo_about_description', 'We have been delivering quality safety solutions since 2012.'),
('seo_products_title', 'ESD Products - AMN International'),
('seo_products_description', 'Anti-static bags, strapping, mats, footwear, and testing equipment.'),
('seo_services_title', 'Our Services'),
('seo_services_description', 'Consultancy, sourcing, training, and compliance audits.'),
('seo_process_title', 'Our Process'),
('seo_process_description', 'Request a quote, we source, we produce, we deliver.'),
('seo_gallery_title', 'Image Gallery'),
('seo_gallery_description', 'See our products and solutions in action.'),
('seo_news_title', 'News & Articles'),
('seo_news_description', 'Latest updates from AMN International.'),
('seo_contact_title', 'Contact Us'),
('seo_contact_description', 'Questions? Reach out to our team today.'),
('seo_quote_title', 'Request a Quote'),
('seo_quote_description', 'Get a fast, no-obligation quote for your ESD needs.'),
('hero_image', ''),
('categories_title', 'Categories'),
('featured_title', 'Featured Products'),
('new_title', 'New Arrivals'),
('popular_title', 'Popular Products'),
('stats_years', '15+'),
('stats_clients', '500+'),
('stats_shipments', '10000+'),
('stats_countries', '20+'),
('footer_about', 'We provide the highest quality electronic safety products and solutions.'),
('nav_links', 'Home|/\nProducts|/products\nServices|/services\nImport Process|/import-process\nGallery|/gallery\nNews & Articles|/news\nAbout Us|/about\nContact Us|/contact');

INSERT INTO `product_specs` (`product_id`, `label`, `value`, `sort_order`) VALUES
(1, 'Material', 'LDPE with ESD shielding layer', 1),
(1, 'Dimensions', '12 x 15 inches (30 x 38 cm)', 2),
(1, 'Wall thickness', '6 mil (0.15 mm)', 3),
(2, 'Width', '2 inches', 1),
(2, 'Length', '500 feet (152 m)', 2),
(2, 'Conductive', 'Yes - carbon-threaded', 3),
(3, 'Size', 'Standard wrist band', 1),
(3, 'Included', '12 straps, 12 coils, 12 snap buttons', 2);
