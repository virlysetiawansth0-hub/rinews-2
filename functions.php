<?php
/**
 * RINEWS.ID — Portal Berita Republik Indonesia
 * Functions & Definitions
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'RINEWS_VERSION', '2.3.3' );
define( 'RINEWS_DIR', get_template_directory() );
define( 'RINEWS_URI', get_template_directory_uri() );

/**
 * Setup dasar tema RINEWS.ID (Kompatibel WordPress 7.1, Website Lama & Semua Plugin)
 */
function rinews_theme_setup() {
    load_theme_textdomain( 'rinews', RINEWS_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'editor-styles' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'appearance-tools' );
    add_theme_support( 'border' );
    add_theme_support( 'link-color' );
    add_theme_support( 'custom-spacing' );
    add_theme_support( 'custom-line-height' );
    add_theme_support( 'custom-units' );
    add_theme_support( 'block-template-parts' );

    // Dukungan penuh WooCommerce
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );

    add_theme_support( 'custom-logo', array(
        'height'      => 120,
        'width'       => 380,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ) );
    add_theme_support( 'customize-selective-refresh-widgets' );

    register_nav_menus( array(
        'primary' => __( 'Menu Utama Kategori RINEWS (Header Merah)', 'rinews' ),
        'footer'  => __( 'Menu Footer RINEWS', 'rinews' ),
    ) );

    if ( function_exists( 'rinews_register_widget_image_sizes' ) ) {
        rinews_register_widget_image_sizes();
    } else {
        add_image_size( 'rinews-slide', 1052, 786, true );
        add_image_size( 'rinews-card', 526, 393, true );
        add_image_size( 'rinews-thumb', 263, 197, true );
    }
}
add_action( 'after_setup_theme', 'rinews_theme_setup' );

/**
 * Konversi & sesuaikan otomatis ukuran gambar unggahan ke rasio 526x393 (termasuk upscaling/crop rapi)
 */
function rinews_auto_fit_image_dimensions( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
    if ( ! $crop || $dest_w <= 0 || $dest_h <= 0 || $orig_w <= 0 || $orig_h <= 0 ) {
        return $default;
    }

    $is_rinews_target = function_exists( 'rinews_is_managed_image_dimensions' )
        ? rinews_is_managed_image_dimensions( $dest_w, $dest_h )
        : in_array( array( (int) $dest_w, (int) $dest_h ), array( array( 526, 393 ), array( 1052, 786 ), array( 263, 197 ) ), true );

    if ( ! $is_rinews_target ) {
        return $default;
    }

    $size_ratio = max( $dest_w / $orig_w, $dest_h / $orig_h );
    $crop_w     = (int) round( $dest_w / $size_ratio );
    $crop_h     = (int) round( $dest_h / $size_ratio );

    $crop_w = max( 1, min( $orig_w, $crop_w ) );
    $crop_h = max( 1, min( $orig_h, $crop_h ) );

    $s_x = (int) floor( ( $orig_w - $crop_w ) / 2 );
    $s_y = (int) floor( ( $orig_h - $crop_h ) / 2 );

    return array( 0, 0, $s_x, $s_y, (int) $dest_w, (int) $dest_h, $crop_w, $crop_h );
}
add_filter( 'image_resize_dimensions', 'rinews_auto_fit_image_dimensions', 10, 6 );

/**
 * Muat Stylesheet & Script Frontend + Admin Widget Uploader
 */
function rinews_enqueue_assets() {
    wp_enqueue_style(
        'rinews-google-fonts',
        'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap',
        array(),
        null
    );
    wp_enqueue_style( 'rinews-style', get_stylesheet_uri(), array( 'rinews-google-fonts' ), RINEWS_VERSION );

    $portal_js_rel = file_exists( RINEWS_DIR . '/assets/js/rinews-portal.js' )
        ? '/assets/js/rinews-portal.js'
        : '/rinews-portal.js';

    wp_enqueue_script(
        'rinews-portal-js',
        RINEWS_URI . $portal_js_rel,
        array(),
        RINEWS_VERSION,
        array(
            'in_footer' => true,
            'strategy'  => 'defer',
        )
    );
}
add_action( 'wp_enqueue_scripts', 'rinews_enqueue_assets' );

function rinews_admin_widget_scripts( $hook ) {
    if ( 'widgets.php' === $hook || 'customize.php' === $hook ) {
        wp_enqueue_media();
        $admin_js_rel = file_exists( RINEWS_DIR . '/assets/js/rinews-admin-widgets.js' )
            ? '/assets/js/rinews-admin-widgets.js'
            : '/rinews-admin-widgets.js';

        wp_enqueue_script(
            'rinews-admin-widgets',
            RINEWS_URI . $admin_js_rel,
            array( 'jquery' ),
            RINEWS_VERSION,
            true
        );
    }
}
add_action( 'admin_enqueue_scripts', 'rinews_admin_widget_scripts' );

/**
 * Daftarkan Semua Area Widget WordPress (Appearance -> Widgets)
 * Memudahkan pengeditan tiap bagian portal langsung dari Dashboard WordPress.
 */
function rinews_register_widget_areas() {
    $areas = array(
        'widget-header-logo' => array(
            'name'        => __( '0. Header — Logo Tengah Portal', 'rinews' ),
            'description' => __( 'Pasang atau edit Widget RINEWS: Logo Header Tengah (atau widget Gambar/HTML) untuk mengatur logo & judul utama di posisi tengah header.', 'rinews' ),
        ),
        'widget-header-bawah-tagline' => array(
            'name'        => __( '0b. Header — Widget Gambar Di Bawah Tagline (Tengah)', 'rinews' ),
            'description' => __( 'Pasang atau edit Widget Gambar / Iklan posisi tengah tepat di bawah tulisan REPUBLIC OF INDONESIA NEWS MEDIA.', 'rinews' ),
        ),
        'widget-indeks-iklan' => array(
            'name'        => __( '1. Indeks Pencarian — 3 Space Gambar Iklan', 'rinews' ),
            'description' => __( 'Tambahkan hingga 3 Widget Gambar Iklan di bawah kolom pencarian.', 'rinews' ),
        ),
        'widget-berita-viral' => array(
            'name'        => __( '2. Kolom Berita Viral (Pembaca Terbanyak)', 'rinews' ),
            'description' => __( 'Gunakan Widget RINEWS: Berita Viral untuk mengatur jumlah/judul.', 'rinews' ),
        ),
        'widget-slide-berita' => array(
            'name'        => __( '3. Kolom Slide Berita Baru & 3 Kategori Teratas', 'rinews' ),
            'description' => __( 'Gunakan Widget RINEWS: Slide Berita Baru untuk mengedit sumber kategori slide & 3 kolom bawahnya.', 'rinews' ),
        ),
        'widget-iklan-panjang-1' => array(
            'name'        => __( '4. Iklan Panjang 1 (Leaderboard Atas)', 'rinews' ),
            'description' => __( 'Pasang banner iklan horizontal pertama di bawah hero.', 'rinews' ),
        ),
        'widget-pos-baru' => array(
            'name'        => __( '5. Pos Baru — 1 Grup Kiri & Kanan (20 Berita/Hal + Paginasi)', 'rinews' ),
            'description' => __( 'Satu grup 2 kolom (kiri & kanan) menampilkan 20 berita terbaru hingga terlama dengan nomor halaman serta tombol Sebelumnya & Selanjutnya.', 'rinews' ),
        ),
        'widget-pos-baru-kiri' => array(
            'name'        => __( '5b. Pos Baru — Kolom Kiri (Cadangan Lama)', 'rinews' ),
            'description' => __( 'Slot cadangan lama; gunakan area "5. Pos Baru — 1 Grup Kiri & Kanan" untuk tampilan 1 grup terpadu.', 'rinews' ),
        ),
        'widget-pos-baru-kanan' => array(
            'name'        => __( '6. Pos Baru — Kolom Kanan (Cadangan Lama)', 'rinews' ),
            'description' => __( 'Slot cadangan lama; gunakan area "5. Pos Baru — 1 Grup Kiri & Kanan" untuk tampilan 1 grup terpadu.', 'rinews' ),
        ),
        'widget-iklan-panjang-2' => array(
            'name'        => __( '7. Iklan Panjang 2 (Leaderboard Tengah)', 'rinews' ),
            'description' => __( 'Pasang banner iklan horizontal kedua di atas blok kategori.', 'rinews' ),
        ),
        'widget-kategori-utama' => array(
            'name'        => __( '8. Integrasi Kategori Horizontal (Internasional, Nasional, Regional, Buser, Olah Raga, Bisnis)', 'rinews' ),
            'description' => __( 'Tambahkan & edit Widget RINEWS: Integrasi Kategori Berita untuk setiap kategori horizontal.', 'rinews' ),
        ),
        'widget-kategori-vertikal' => array(
            'name'        => __( '9. Integrasi 3 Kolom Vertikal (Ilmu Pengetahuan, Hiburan, Budaya)', 'rinews' ),
            'description' => __( 'Tambahkan 3 Widget RINEWS: Integrasi Kategori Berita (mode Vertikal) di sini.', 'rinews' ),
        ),
        'widget-kolom-hitam' => array(
            'name'        => __( '10. Kolom Hitam Bawah (Komentar, Arsip 12 Bulan, Mungkin Terlewatkan H-1)', 'rinews' ),
            'description' => __( 'Atur urutan atau judul kolom Komentar, Arsip 12 Bulan, dan Mungkin Terlewatkan.', 'rinews' ),
        ),
        'widget-tiktok-video' => array(
            'name'        => __( '11. Integrasi TikTok Video', 'rinews' ),
            'description' => __( 'Pasang Widget RINEWS: Integrasi Video TikTok Otomatis.', 'rinews' ),
        ),
        'widget-youtube-video' => array(
            'name'        => __( '12. Integrasi Video Berita YouTube', 'rinews' ),
            'description' => __( 'Pasang Widget RINEWS: Integrasi Video YouTube Otomatis.', 'rinews' ),
        ),
        'widget-iklan-footer-3' => array(
            'name'        => __( '13. 3 Gambar Iklan Bawah', 'rinews' ),
            'description' => __( 'Tambahkan 3 Widget Gambar Iklan di atas footer.', 'rinews' ),
        ),
        'widget-iklan-panjang-bawah' => array(
            'name'        => __( '14. Iklan Panjang Paling Bawah', 'rinews' ),
            'description' => __( 'Pasang banner iklan panjang paling bawah sebelum copyright.', 'rinews' ),
        ),
        'widget-sidebar-artikel' => array(
            'name'        => __( '15. Sidebar Halaman Baca Artikel', 'rinews' ),
            'description' => __( 'Widget yang tampil di samping artikel tunggal.', 'rinews' ),
        ),
        'widget-live-streaming' => array(
            'name'        => __( '16. LIVE Streaming Portrait & TikTok Live (Tengah Atas Copyright)', 'rinews' ),
            'description' => __( 'Widget tersendiri untuk mengatur siaran langsung Portrait 9:16, izin penyiar umum, akun TikTok Live personal, dan peraturan di bawah layar.', 'rinews' ),
        ),
    );

    foreach ( $areas as $id => $args ) {
        register_sidebar( array(
            'name'          => $args['name'],
            'id'            => $id,
            'description'   => $args['description'],
            'before_widget' => '<div id="%1$s" class="rn-widget-block %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="rn-widget-title">',
            'after_title'   => '</h3>',
        ) );
    }
}
add_action( 'widgets_init', 'rinews_register_widget_areas' );

/**
 * Helper Gambar Fallback agar tidak pernah broken image
 */
function rinews_get_post_image_url( $post_id = null, $size = 'rinews-card' ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    if ( $post_id && has_post_thumbnail( $post_id ) ) {
        $src = get_the_post_thumbnail_url( $post_id, $size );
        if ( $src ) {
            return $src;
        }
    }
    // Fallback ke gambar default yang dibundel di dalam tema
    return RINEWS_URI . '/assets/images/news_istana_jakarta.jpg';
}

/**
 * Ambil N kalimat pertama dari isi artikel (digunakan di kolom Pos Baru: 3 kalimat teratas)
 */
function rinews_get_first_sentences_excerpt( $post_id = null, $sentence_count = 3 ) {
    $post = get_post( $post_id ? $post_id : get_the_ID() );
    if ( ! $post ) {
        return '';
    }
    $raw = (string) $post->post_content;
    if ( '' === trim( $raw ) ) {
        $raw = (string) $post->post_excerpt;
    }
    if ( function_exists( 'excerpt_remove_blocks' ) ) {
        $raw = excerpt_remove_blocks( $raw );
    }
    $raw  = strip_shortcodes( $raw );
    $text = wp_strip_all_tags( $raw, true );
    $text = html_entity_decode( $text, ENT_QUOTES, 'UTF-8' );
    $text = preg_replace( '/\s+/u', ' ', trim( (string) $text ) );
    if ( '' === $text ) {
        return '';
    }

    $parts = preg_split(
        '/(?<!\bPT|\bCV|\bTBK|\bDr|\bIr|\bH|\bNo|\bJl|\bSt|\bRp)(?<=[.!?])\s+/iu',
        $text,
        (int) $sentence_count + 1,
        PREG_SPLIT_NO_EMPTY
    );
    if ( ! is_array( $parts ) || empty( $parts ) ) {
        return $text;
    }

    return trim( implode( ' ', array_slice( $parts, 0, (int) $sentence_count ) ) );
}

/**
 * Muat Modul Tema RINEWS dengan aman (mendukung struktur /inc/ maupun root)
 */
function rinews_require_module( $filename ) {
    $inc_path  = RINEWS_DIR . '/inc/' . $filename;
    $root_path = RINEWS_DIR . '/' . $filename;
    if ( file_exists( $inc_path ) ) {
        require_once $inc_path;
    } elseif ( file_exists( $root_path ) ) {
        require_once $root_path;
    }
}

rinews_require_module( 'widget-image-sizes.php' );
rinews_require_module( 'tiktok-integration.php' );
rinews_require_module( 'seo-and-views.php' );
rinews_require_module( 'media-share.php' );
rinews_require_module( 'customizer.php' );
rinews_require_module( 'schedule-and-shortcodes.php' );
rinews_require_module( 'widgets.php' );
rinews_require_module( 'admin-setup.php' );
