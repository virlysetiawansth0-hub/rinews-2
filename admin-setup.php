<?php
/**
 * RINEWS.ID — Setup Otomatis Kategori, Submenu, Halaman Redaksi, Gambar, Berita Awal & Widget
 * Membantu pengguna mengatur seluruh struktur website di WordPress hanya dengan 1 klik.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rinews_add_setup_menu() {
    add_theme_page(
        __( 'Setup Portal RINEWS.ID', 'rinews' ),
        __( 'Setup RINEWS.ID', 'rinews' ),
        'manage_options',
        'rinews-setup',
        'rinews_render_setup_page'
    );
}
add_action( 'admin_menu', 'rinews_add_setup_menu' );

function rinews_create_categories_and_menu() {
    $structure = array(
        'INTERNASIONAL'    => array( 'United State', 'Eropa', 'Asia', 'Timur Tengah' ),
        'NASIONAL'         => array( 'Pemerintahan', 'Politik', 'Hukum', 'Istana' ),
        'REGIONAL'         => array( 'Aceh', 'Banten', 'Jawa Barat', 'Jawa Tengah', 'Jawa Timur', 'Bali', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Nusa Tenggara', 'Papua' ),
        'BUSER BERITA'     => array( 'TNI/Polri', 'KPK', 'Hukum/Politik' ),
        'OLAH RAGA'        => array(),
        'ILMU PENGETAHUAN' => array(),
        'BUDAYA'           => array(),
        'HIBURAN'          => array(),
        'BISNIS'           => array(),
    );

    $cat_ids = array();

    foreach ( $structure as $parent_name => $children ) {
        $parent_term = term_exists( $parent_name, 'category' );
        if ( ! $parent_term ) {
            $parent_term = wp_insert_term( $parent_name, 'category' );
        }
        if ( is_wp_error( $parent_term ) ) {
            continue;
        }
        $parent_id = (int) $parent_term['term_id'];
        $cat_ids[ $parent_name ] = $parent_id;

        foreach ( $children as $child_name ) {
            $child_term = term_exists( $child_name, 'category', $parent_id );
            if ( ! $child_term ) {
                $child_term = wp_insert_term( $child_name, 'category', array( 'parent' => $parent_id ) );
            }
            if ( ! is_wp_error( $child_term ) ) {
                $cat_ids[ $child_name ] = (int) $child_term['term_id'];
            }
        }
    }

    // Buat Halaman Editorial (REDAKSI, PRIVASI POLICY, dll)
    $pages = array(
        'REDAKSI'             => 'Susunan Redaksi Resmi RINEWS.ID — PT. Virnanda Creator Productions.',
        'PRIVASI POLICY'      => 'Kebijakan Privasi Pengguna Portal Berita RINEWS.ID.',
        'TENTANG KAMI'        => 'RINEWS.ID adalah Media Berita Republik Indonesia.',
        'STRUKTUR REDAKSI'    => 'Struktur Organisasi Redaksi RINEWS.ID.',
        'PEDOMAN MEDIA SIBER' => 'Pedoman Pemberitaan Media Siber Dewan Pers.',
        'KONTAK IKLAN'        => 'Informasi Pemasangan Iklan & Kerja Sama PT. Virnanda Creator Productions.',
    );

    $page_ids = array();
    foreach ( $pages as $title => $content ) {
        $existing_q = new WP_Query( array(
            'post_type'              => 'page',
            'title'                  => $title,
            'post_status'            => 'any',
            'posts_per_page'         => 1,
            'no_found_rows'          => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ) );
        $existing = $existing_q->have_posts() ? $existing_q->posts[0] : null;
        if ( ! $existing ) {
            $pid = wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ) );
            if ( ! is_wp_error( $pid ) ) {
                $page_ids[ $title ] = $pid;
            }
        } else {
            $page_ids[ $title ] = $existing->ID;
        }
    }

    // Buat Navigasi Menu Utama jika belum terpasang
    $menu_name = 'Menu Utama RINEWS.ID';
    $menu_obj  = wp_get_nav_menu_object( $menu_name );
    if ( ! $menu_obj ) {
        $menu_id = wp_create_nav_menu( $menu_name );
        if ( is_wp_error( $menu_id ) ) {
            return $cat_ids;
        }
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'  => 'BERANDA',
            'menu-item-url'    => home_url( '/' ),
            'menu-item-status' => 'publish',
        ) );

        foreach ( $structure as $parent_name => $children ) {
            if ( empty( $cat_ids[ $parent_name ] ) ) continue;
            $parent_menu_id = wp_update_nav_menu_item( $menu_id, 0, array(
                'menu-item-title'     => $parent_name,
                'menu-item-object'    => 'category',
                'menu-item-object-id' => $cat_ids[ $parent_name ],
                'menu-item-type'      => 'taxonomy',
                'menu-item-status'    => 'publish',
            ) );

            foreach ( $children as $child_name ) {
                if ( empty( $cat_ids[ $child_name ] ) ) continue;
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => $child_name,
                    'menu-item-object'    => 'category',
                    'menu-item-object-id' => $cat_ids[ $child_name ],
                    'menu-item-type'      => 'taxonomy',
                    'menu-item-parent-id' => $parent_menu_id,
                    'menu-item-status'    => 'publish',
                ) );
            }
        }

        foreach ( array( 'REDAKSI', 'PRIVASI POLICY' ) as $p_title ) {
            if ( ! empty( $page_ids[ $p_title ] ) ) {
                wp_update_nav_menu_item( $menu_id, 0, array(
                    'menu-item-title'     => $p_title,
                    'menu-item-object'    => 'page',
                    'menu-item-object-id' => $page_ids[ $p_title ],
                    'menu-item-type'      => 'post_type',
                    'menu-item-status'    => 'publish',
                ) );
            }
        }

        $locations = get_theme_mod( 'nav_menu_locations', array() );
        $locations['primary'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    return $cat_ids;
}

function rinews_seed_default_widgets( $cat_ids ) {
    $sidebars = get_option( 'sidebars_widgets', array() );
    $widget_kat = get_option( 'widget_rinews_widget_kategori', array() );
    $widget_iklan = get_option( 'widget_rinews_widget_iklan', array() );

    // Pre-populate Widget Integrasi Kategori Horizontal jika masih kosong
    if ( empty( $sidebars['widget-kategori-utama'] ) ) {
        $horizontal_list = array( 'INTERNASIONAL', 'NASIONAL', 'REGIONAL', 'BUSER BERITA', 'OLAH RAGA', 'BISNIS' );
        $assigned = array();
        $idx = 10;
        foreach ( $horizontal_list as $cat_name ) {
            $cid = isset( $cat_ids[ $cat_name ] ) ? $cat_ids[ $cat_name ] : 0;
            $widget_kat[ $idx ] = array(
                'title'     => $cat_name,
                'cat_id'    => $cid,
                'count'     => 4,
                'layout'    => 'horizontal',
                'show_subs' => true,
                'header_bg' => '',
            );
            $assigned[] = 'rinews_widget_kategori-' . $idx;
            $idx++;
        }
        $sidebars['widget-kategori-utama'] = $assigned;
    }

    // Pre-populate 3 Kolom Vertikal (Ilmu Pengetahuan, Hiburan, Budaya)
    if ( empty( $sidebars['widget-kategori-vertikal'] ) ) {
        $vertical_list = array( 'ILMU PENGETAHUAN', 'HIBURAN', 'BUDAYA' );
        $assigned = array();
        $idx = 30;
        foreach ( $vertical_list as $cat_name ) {
            $cid = isset( $cat_ids[ $cat_name ] ) ? $cat_ids[ $cat_name ] : 0;
            $widget_kat[ $idx ] = array(
                'title'     => $cat_name,
                'cat_id'    => $cid,
                'count'     => 3,
                'layout'    => 'vertical',
                'show_subs' => false,
                'header_bg' => '',
            );
            $assigned[] = 'rinews_widget_kategori-' . $idx;
            $idx++;
        }
        $sidebars['widget-kategori-vertikal'] = $assigned;
    }

    // Pre-populate 3 Iklan Gambar di Indeks Pencarian
    if ( empty( $sidebars['widget-indeks-iklan'] ) ) {
        $ads = array(
            array( 'sponsor' => 'IKLAN 1 · BANK NUSANTARA', 'title' => 'Solusi Tabungan Bisnis & QRIS', 'image_url' => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg' ),
            array( 'sponsor' => 'IKLAN 2 · GARUDA FIBER 5G', 'title' => 'Internet Korporat 38 Provinsi', 'image_url' => RINEWS_URI . '/assets/images/news_teknologi_riset.jpg' ),
            array( 'sponsor' => 'IKLAN 3 · NUSANTARA LOGISTIK', 'title' => 'Kargo Pelabuhan & Udara Cepat', 'image_url' => RINEWS_URI . '/assets/images/news_regional_indonesia.jpg' ),
        );
        $assigned = array();
        $idx = 10;
        foreach ( $ads as $ad ) {
            $widget_iklan[ $idx ] = array(
                'sponsor'   => $ad['sponsor'],
                'title'     => $ad['title'],
                'image_url' => $ad['image_url'],
                'link_url'  => home_url( '/kontak-iklan/' ),
                'mode'      => 'sidebar',
            );
            $assigned[] = 'rinews_widget_iklan-' . $idx;
            $idx++;
        }
        $sidebars['widget-indeks-iklan'] = $assigned;
    }

    // Pre-populate Logo Header Tengah di Area Widget Header jika masih kosong
    $widget_header_logo = get_option( 'widget_rinews_widget_header_logo', array() );
    if ( empty( $sidebars['widget-header-logo'] ) ) {
        $widget_header_logo[10] = array(
            'logo_image_url'  => '',
            'logo_max_height' => 82,
            'link_url'        => home_url( '/' ),
            'show_tagline'    => 1,
            'tagline_id'      => get_theme_mod( 'rinews_tagline_id', 'MEDIA BERITA REPUBLIK INDONESIA' ),
            'tagline_en'      => get_theme_mod( 'rinews_tagline_en', 'REPUBLIC OF INDONESIA NEWS MEDIA' ),
        );
        $sidebars['widget-header-logo'] = array( 'rinews_widget_header_logo-10' );
        update_option( 'widget_rinews_widget_header_logo', $widget_header_logo );
    }

    // Pre-populate Widget Gambar Di Bawah Tagline Header (Posisi Tengah)
    $widget_header_img = get_option( 'widget_rinews_widget_header_image', array() );
    if ( empty( $sidebars['widget-header-bawah-tagline'] ) ) {
        $widget_header_img[10] = array(
            'image_url'  => RINEWS_URI . '/assets/images/iklan_korporat_digital.jpg',
            'alt_text'   => 'RINEWS.ID — Portal Berita & Mitra Media Nasional',
            'badge_text' => 'RINEWS MEDIA NETWORK · 38 PROVINSI',
            'link_url'   => home_url( '/kontak-iklan/' ),
            'max_width'  => 728,
            'max_height' => 120,
            'object_fit' => 'cover',
        );
        $sidebars['widget-header-bawah-tagline'] = array( 'rinews_widget_header_image-10' );
        update_option( 'widget_rinews_widget_header_image', $widget_header_img );
    }

    // Pre-populate Pos Baru 1 Grup Kiri & Kanan (20 Berita/Hal + Paginasi Terbaru-Terlama)
    if ( empty( $sidebars['widget-pos-baru'] ) ) {
        $widget_kat[5] = array(
            'title'           => 'POS BARU',
            'cat_id'          => 0,
            'count'           => 20,
            'offset'          => 0,
            'layout'          => 'grid_pos_baru',
            'show_subs'       => false,
            'show_pagination' => true,
            'header_bg'       => '',
        );
        $sidebars['widget-pos-baru'] = array( 'rinews_widget_kategori-5' );
    }

    update_option( 'widget_rinews_widget_kategori', $widget_kat );
    update_option( 'widget_rinews_widget_iklan', $widget_iklan );
    update_option( 'sidebars_widgets', $sidebars );

    if ( function_exists( 'rinews_ensure_tiktok_widgets_active' ) ) {
        rinews_ensure_tiktok_widgets_active();
    }

    $sidebars_after = get_option( 'sidebars_widgets', array() );
    if ( empty( $sidebars_after['widget-youtube-video'] ) ) {
        $widget_yt = get_option( 'widget_rinews_widget_youtube', array() );
        if ( empty( $widget_yt[10] ) ) {
            $widget_yt[10] = array(
                'title'       => 'VIDEO BERITA (YOUTUBE)',
                'channel_id'  => get_theme_mod( 'rinews_youtube_cid', 'UC1IRNb-AANRUXdgEOlevKWw' ),
                'channel_url' => get_theme_mod( 'rinews_youtube_url', 'https://www.youtube.com/@rinews-berita-video' ),
            );
            update_option( 'widget_rinews_widget_youtube', $widget_yt );
        }
        $sidebars_after['widget-youtube-video'] = array( 'rinews_widget_youtube-10' );
        update_option( 'sidebars_widgets', $sidebars_after );
    }
}

function rinews_render_setup_page() {
    $done = false;
    if ( isset( $_POST['rinews_run_setup'] ) && check_admin_referer( 'rinews_setup_nonce' ) ) {
        $cat_ids = rinews_create_categories_and_menu();
        rinews_seed_default_widgets( $cat_ids );
        $done = true;
    }
    ?>
    <div class="wrap">
        <h1>Setup Otomatis Portal Berita RINEWS.ID</h1>
        <?php if ( $done ) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong>Berhasil!</strong> 12 Kategori + 22 Submenu, Menu Navigasi Utama, Halaman Redaksi/Privasi, serta Widget Integrasi Kategori &amp; Iklan telah dipasang. Silakan buka <strong>Tampilan &rarr; Widget</strong> untuk mengedit integrasi tiap kategori.</p>
            </div>
        <?php endif; ?>
        <div class="card" style="max-width:760px; padding:20px;">
            <h2>1-Klik Bangun Kategori, Submenu &amp; Widget Integrasi (Aman untuk Website Lama)</h2>
            <p style="background:#ECFDF5; border-left:4px solid #10B981; padding:10px 12px; color:#065F46;">
                <strong>Aman untuk Website WordPress Lama:</strong> Proses ini hanya menambahkan kategori, halaman, atau widget yang belum ada. Pos berita lama, komentar, pengguna, maupun pengaturan plugin (Yoast/Rank Math, Elementor, WooCommerce, CF7, dll.) <strong>tidak akan dihapus atau ditimpa</strong>.
            </p>
            <p>Klik tombol di bawah untuk otomatis memeriksa dan melengkapi:</p>
            <ul style="list-style:disc; padding-left:20px;">
                <li>Semua 12 menu utama + submenu (Internasional, Nasional, Regional 11 wilayah, Buser Berita).</li>
                <li>Menu Navigasi Merah RINEWS.ID (jika belum ada menu sebelumnya).</li>
                <li>Widget Integrasi Kategori (Horizontal &amp; Vertikal) dan 3 Widget Gambar Iklan di <strong>Tampilan &rarr; Widget</strong> agar langsung siap diedit ulang &amp; diatur jam tayangnya.</li>
            </ul>
            <form method="post">
                <?php wp_nonce_field( 'rinews_setup_nonce' ); ?>
                <p><button type="submit" name="rinews_run_setup" class="button button-primary button-hero">Jalankan Setup Portal RINEWS.ID Sekarang</button></p>
            </form>
            <p style="margin-top:16px; display:flex; flex-wrap:wrap; gap:8px;">
                <a href="<?php echo esc_url( admin_url( 'themes.php?page=rinews-schedule' ) ); ?>" class="button button-primary">Atur Jam Tayang &amp; Shortcode &rarr;</a>
                <a href="<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>" class="button">Buka Pengaturan 17 Area Widget &rarr;</a>
                <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button">Buka Sesuaikan Tema &rarr;</a>
            </p>
        </div>
    </div>
    <?php
}
