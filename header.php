<?php
/**
 * RINEWS.ID — Header & Navigasi Kategori + Media Share
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$rinews_color_mode = 'light' === get_theme_mod( 'rinews_color_mode', 'dark' ) ? 'light' : 'dark';
?><!doctype html>
<html <?php language_attributes(); ?> data-rn-theme="<?php echo esc_attr( $rinews_color_mode ); ?>">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#070B14" />
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'rn-theme-' . $rinews_color_mode ); ?>>
<?php wp_body_open(); ?>

<header class="rn-container rn-masthead-wrap">
    <div class="rn-masthead-card">
        <div class="rn-masthead-top">
            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="font-weight:900;">
                    <?php echo esc_html( get_theme_mod( 'rinews_domain_label', 'RINEWS.ID' ) ); ?>
                </a>
                <span class="rn-masthead-sep">|</span>
                <span class="rn-masthead-clock"><?php echo esc_html( function_exists( 'rinews_get_broadcast_clock_label' ) ? rinews_get_broadcast_clock_label() : wp_date( 'l, j F Y · H:i' ) . ' WIB' ); ?></span>
            </div>
            <div class="rn-masthead-right">
                <span class="rn-masthead-badge">PORTAL BERITA INDONESIA</span>
                <button type="button" class="rn-theme-toggle-btn js-rn-theme-toggle" aria-label="<?php esc_attr_e( 'Ubah Mode Warna Gelap atau Terang', 'rinews' ); ?>">
                    <?php echo 'light' === $rinews_color_mode ? 'Mode: Terang' : 'Mode: Gelap'; ?>
                </button>
            </div>
        </div>

        <div class="rn-masthead-center">
            <?php
            if ( is_active_sidebar( 'widget-header-logo' ) ) {
                dynamic_sidebar( 'widget-header-logo' );
            } else {
                the_widget( 'RINEWS_Widget_Header_Logo' );
            }
            ?>
            <div class="rn-masthead-bottom-widget">
                <?php
                if ( is_active_sidebar( 'widget-header-bawah-tagline' ) ) {
                    dynamic_sidebar( 'widget-header-bawah-tagline' );
                } else {
                    the_widget( 'RINEWS_Widget_Header_Image' );
                }
                ?>
            </div>
        </div>
    </div>

    <nav class="rn-navbar" id="rn-main-navbar" aria-label="Navigasi Kategori Utama">
        <div class="rn-navbar-inner">
            <div class="rn-navbar-left">
                <button
                    type="button"
                    class="rn-hamburger-btn js-rn-nav-toggle"
                    aria-expanded="false"
                    aria-controls="rn-nav-drawer"
                    aria-label="<?php esc_attr_e( 'Buka atau Tutup Menu Navigasi', 'rinews' ); ?>"
                >
                    <span class="rn-hamburger-lines" aria-hidden="true">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                    <span class="rn-hamburger-text">MENU</span>
                </button>

                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="rn-nav-home-link">
                    BERANDA
                </a>

                <?php if ( is_category() ) : ?>
                    <span class="rn-nav-current-pill"><?php single_cat_title(); ?></span>
                <?php elseif ( is_search() ) : ?>
                    <span class="rn-nav-current-pill"><?php esc_html_e( 'PENCARIAN', 'rinews' ); ?></span>
                <?php endif; ?>
            </div>

            <div class="rn-media-share-box">
                <div class="rn-media-share-label">MEDIA SHARE</div>
                <?php rinews_render_media_share_bar( true ); ?>
            </div>
        </div>

        <div id="rn-nav-drawer" class="rn-nav-drawer" aria-hidden="true">
            <div class="rn-nav-drawer-head">
                <span>KATEGORI &amp; MENU PORTAL</span>
                <button type="button" class="rn-nav-drawer-close js-rn-nav-close" aria-label="<?php esc_attr_e( 'Tutup Menu', 'rinews' ); ?>">&times; Tutup</button>
            </div>
            <?php
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu( array(
                    'theme_location' => 'primary',
                    'menu_class'     => 'rn-nav-list',
                    'container'      => false,
                    'depth'          => 2,
                ) );
            } else {
                ?>
                <ul class="rn-nav-list">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">BERANDA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/internasional/' ) ); ?>">INTERNASIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/nasional/' ) ); ?>">NASIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/regional/' ) ); ?>">REGIONAL</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/buser-berita/' ) ); ?>">BUSER BERITA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/olah-raga/' ) ); ?>">OLAH RAGA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/ilmu-pengetahuan/' ) ); ?>">ILMU PENGETAHUAN</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/budaya/' ) ); ?>">BUDAYA</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/hiburan/' ) ); ?>">HIBURAN</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/category/bisnis/' ) ); ?>">BISNIS</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/redaksi/' ) ); ?>">REDAKSI</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/privasi-policy/' ) ); ?>">PRIVASI POLICY</a></li>
                </ul>
                <?php
            }
            ?>
        </div>
    </nav>
</header>
