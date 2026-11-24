<?php
/**
 * RINEWS.ID — WordPress Customizer (Tampilan -> Sesuaikan)
 * Pengaturan Logo, Medsos Share, Akun YouTube & TikTok, dan Copyright.
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function rinews_customize_register( $wp_customize ) {
    // Panel Utama RINEWS
    $wp_customize->add_panel( 'rinews_panel', array(
        'title'       => __( 'Pengaturan Portal RINEWS.ID', 'rinews' ),
        'description' => __( 'Ubah identitas media, akun YouTube & TikTok, tautan Medsos Share, dan footer.', 'rinews' ),
        'priority'    => 20,
    ) );

    // Seksi 1: Identitas & Masthead
    $wp_customize->add_section( 'rinews_masthead_sec', array(
        'title' => __( '1. Masthead & Identitas', 'rinews' ),
        'panel' => 'rinews_panel',
    ) );

    $wp_customize->add_setting( 'rinews_domain_label', array(
        'default'           => 'RINEWS.ID',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'rinews_domain_label', array(
        'label'   => __( 'Label Domain Kiri Atas', 'rinews' ),
        'section' => 'rinews_masthead_sec',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'rinews_tagline_id', array(
        'default'           => 'MEDIA BERITA REPUBLIK INDONESIA',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'rinews_tagline_id', array(
        'label'   => __( 'Tagline Indonesia', 'rinews' ),
        'section' => 'rinews_masthead_sec',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'rinews_tagline_en', array(
        'default'           => 'REPUBLIC OF INDONESIA NEWS MEDIA',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'rinews_tagline_en', array(
        'label'   => __( 'Tagline Inggris', 'rinews' ),
        'section' => 'rinews_masthead_sec',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'rinews_color_mode', array(
        'default'           => 'dark',
        'sanitize_callback' => function ( $val ) {
            return 'light' === $val ? 'light' : 'dark';
        },
    ) );
    $wp_customize->add_control( 'rinews_color_mode', array(
        'label'   => __( 'Pewarnaan Utama Web (Versi Dark / Terang)', 'rinews' ),
        'section' => 'rinews_masthead_sec',
        'type'    => 'select',
        'choices' => array(
            'dark'  => __( 'Versi Dark / Mode Gelap (Default)', 'rinews' ),
            'light' => __( 'Versi Terang / Mode Standar', 'rinews' ),
        ),
    ) );

    // Seksi 2: Media Share & SEO Medsos
    $wp_customize->add_section( 'rinews_social_sec', array(
        'title' => __( '2. Akun Medsos & SEO Share', 'rinews' ),
        'panel' => 'rinews_panel',
    ) );

    $social_fields = array(
        'rinews_facebook_url'   => array( 'URL Halaman Facebook', 'https://www.facebook.com/rinewsid' ),
        'rinews_instagram_url'  => array( 'URL Instagram', 'https://www.instagram.com/rinews.id/' ),
        'rinews_twitter_url'    => array( 'URL Twitter / X', 'https://x.com/rinewsid' ),
        'rinews_twitter_handle' => array( 'Handle Twitter untuk SEO Card (@...)', '@rinewsid' ),
        'rinews_youtube_url'    => array( 'URL Channel YouTube', 'https://www.youtube.com/@rinews-berita-video' ),
        'rinews_youtube_cid'    => array( 'Channel ID YouTube (diawali UC...)', 'UC1IRNb-AANRUXdgEOlevKWw' ),
        'rinews_tiktok_url'     => array( 'URL Profil TikTok', 'https://www.tiktok.com/@ri.news.portal?lang=id-ID' ),
        'rinews_tiktok_handle'  => array( 'Username TikTok (@...)', '@ri.news.portal' ),
    );

    foreach ( $social_fields as $key => $meta ) {
        $wp_customize->add_setting( $key, array(
            'default'           => $meta[1],
            'sanitize_callback' => 'sanitize_text_field',
        ) );
        $wp_customize->add_control( $key, array(
            'label'   => $meta[0],
            'section' => 'rinews_social_sec',
            'type'    => 'text',
        ) );
    }

    $wp_customize->add_setting( 'rinews_tiktok_auto_sync', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
    ) );
    $wp_customize->add_control( 'rinews_tiktok_auto_sync', array(
        'label'   => __( 'Aktifkan Integrasi & Sinkronisasi TikTok Otomatis', 'rinews' ),
        'section' => 'rinews_social_sec',
        'type'    => 'checkbox',
    ) );

    $wp_customize->add_setting( 'rinews_tiktok_video_urls', array(
        'default'           => 'https://www.tiktok.com/@ri.news.portal/video/7708887155644452110',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
    $wp_customize->add_control( 'rinews_tiktok_video_urls', array(
        'label'       => __( 'URL Video TikTok (1 URL per baris)', 'rinews' ),
        'description' => __( 'Video akan diputar langsung di modal player TikTok saat diklik.', 'rinews' ),
        'section'     => 'rinews_social_sec',
        'type'        => 'textarea',
    ) );

    // Seksi 3: Footer & Copyright
    $wp_customize->add_section( 'rinews_footer_sec', array(
        'title' => __( '3. Footer & Copyright', 'rinews' ),
        'panel' => 'rinews_panel',
    ) );

    $wp_customize->add_setting( 'rinews_copyright_text', array(
        'default'           => 'COPYRIGHT @RINEWS | PRODUKSI 2026 | PT. VIRNANDA CREATOR PRODUCTIONS',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'rinews_copyright_text', array(
        'label'   => __( 'Teks Copyright Footer', 'rinews' ),
        'section' => 'rinews_footer_sec',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'rinews_customize_register' );
