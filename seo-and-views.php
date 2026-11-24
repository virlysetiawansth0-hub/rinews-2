<?php
/**
 * RINEWS.ID — SEO Lengkap (OpenGraph, Twitter Cards, Schema.org JSON-LD)
 * & Pelacak Pembaca Terbanyak (Berita Viral)
 *
 * @package RINEWS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Hitung dan simpan jumlah pembaca setiap kali artikel tunggal dibuka
 */
function rinews_track_post_views() {
    if ( is_singular( 'post' ) && ! is_admin() && ! wp_doing_ajax() && ! is_feed() && ! ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
        $post_id = get_the_ID();
        if ( ! $post_id ) {
            return;
        }
        $count = (int) get_post_meta( $post_id, 'post_views_count', true );
        $count++;
        update_post_meta( $post_id, 'post_views_count', $count );
    }
}
add_action( 'wp_head', 'rinews_track_post_views' );

function rinews_get_post_views( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    if ( ! $post_id ) {
        return 1200;
    }
    $count = (int) get_post_meta( $post_id, 'post_views_count', true );
    return $count > 0 ? $count : 1200;
}

/**
 * Output SEO Meta Tags lengkap untuk semua media sosial:
 * WhatsApp, Facebook, Instagram, Twitter/X, LinkedIn, Telegram, Google News.
 */
function rinews_output_social_seo_tags() {
    // Kompatibel penuh dengan plugin SEO populer: hindari duplikasi meta tag jika plugin SEO sudah aktif
    if (
        defined( 'WPSEO_VERSION' ) ||
        defined( 'RANK_MATH_VERSION' ) ||
        defined( 'AIOSEO_VERSION' ) ||
        defined( 'SEOPRESS_VERSION' )
    ) {
        return;
    }

    $site_name   = get_bloginfo( 'name' ) ? get_bloginfo( 'name' ) : 'RINEWS.ID — Media Berita Republik Indonesia';
    $default_img = RINEWS_URI . '/assets/images/news_istana_jakarta.jpg';
    $twitter_acc = get_theme_mod( 'rinews_twitter_handle', '@rinewsid' );
    $published   = '';
    $modified    = '';
    $section     = 'Berita Nasional';
    $author_name = 'Redaksi RINEWS.ID';

    if ( is_singular( 'post' ) ) {
        $post = get_queried_object();
        if ( ! ( $post instanceof WP_Post ) ) {
            return;
        }
        $title       = wp_strip_all_tags( get_the_title( $post ) );
        $raw_excerpt = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( (string) $post->post_content ), 26, '...' );
        $description = wp_strip_all_tags( $raw_excerpt );
        $url         = get_permalink( $post );
        $image       = rinews_get_post_image_url( $post->ID, 'full' );
        $type        = 'article';
        $published   = get_the_date( 'c', $post );
        $modified    = get_the_modified_date( 'c', $post );
        $author_name = get_the_author_meta( 'display_name', $post->post_author );
        $cats        = get_the_category( $post->ID );
        $section     = ! empty( $cats ) ? $cats[0]->name : 'Berita Nasional';
    } elseif ( is_category() || is_tag() || is_archive() ) {
        $title       = wp_strip_all_tags( get_the_archive_title() ) . ' — RINEWS.ID';
        $description = wp_strip_all_tags( (string) get_the_archive_description() );
        if ( empty( $description ) ) {
            $description = 'Berita terkini, analisis mendalam, dan laporan terpercaya dari kanal ' . wp_strip_all_tags( (string) single_cat_title( '', false ) ) . ' di RINEWS.ID.';
        }
        $req_path    = isset( $GLOBALS['wp']->request ) ? (string) $GLOBALS['wp']->request : '';
        $url         = home_url( $req_path ? '/' . ltrim( $req_path, '/' ) : '/' );
        $image       = $default_img;
        $type        = 'website';
    } else {
        $title       = $site_name;
        $description = get_bloginfo( 'description' ) ? get_bloginfo( 'description' ) : 'Portal berita nasional, internasional, regional 38 provinsi, buser berita, olahraga, ilmu pengetahuan, budaya, hiburan, dan bisnis Republik Indonesia.';
        $url         = home_url( '/' );
        $image       = $default_img;
        $type        = 'website';
    }
    ?>
    <!-- RINEWS.ID SEO & Social Share Metadata -->
    <meta name="description" content="<?php echo esc_attr( $description ); ?>" />
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
    <link rel="canonical" href="<?php echo esc_url( $url ); ?>" />

    <!-- OpenGraph (Facebook, WhatsApp, LinkedIn, Telegram, Instagram Bio) -->
    <meta property="og:locale" content="id_ID" />
    <meta property="og:site_name" content="<?php echo esc_attr( $site_name ); ?>" />
    <meta property="og:type" content="<?php echo esc_attr( $type ); ?>" />
    <meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
    <meta property="og:description" content="<?php echo esc_attr( $description ); ?>" />
    <meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
    <meta property="og:image" content="<?php echo esc_url( $image ); ?>" />
    <meta property="og:image:secure_url" content="<?php echo esc_url( $image ); ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="675" />
    <meta property="og:image:alt" content="<?php echo esc_attr( $title ); ?>" />
    <?php if ( 'article' === $type ) : ?>
    <meta property="article:published_time" content="<?php echo esc_attr( $published ); ?>" />
    <meta property="article:modified_time" content="<?php echo esc_attr( $modified ); ?>" />
    <meta property="article:section" content="<?php echo esc_attr( $section ); ?>" />
    <?php endif; ?>

    <!-- Twitter / X Cards -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:site" content="<?php echo esc_attr( $twitter_acc ); ?>" />
    <meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr( $description ); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url( $image ); ?>" />

    <!-- Schema.org JSON-LD -->
    <?php if ( 'article' === $type ) : ?>
    <script type="application/ld+json">
    <?php
    echo wp_json_encode( array(
        '@context'         => 'https://schema.org',
        '@type'            => 'NewsArticle',
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id'   => $url,
        ),
        'headline'         => $title,
        'description'      => $description,
        'image'            => array( $image ),
        'datePublished'    => $published,
        'dateModified'     => $modified,
        'articleSection'   => $section,
        'author'           => array(
            '@type' => 'Person',
            'name'  => $author_name ? $author_name : 'Redaksi RINEWS.ID',
        ),
        'publisher'        => array(
            '@type' => 'NewsMediaOrganization',
            'name'  => 'RINEWS.ID — PT. Virnanda Creator Productions',
            'logo'  => array(
                '@type' => 'ImageObject',
                'url'   => $default_img,
            ),
        ),
    ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    ?>
    </script>
    <?php else : ?>
    <script type="application/ld+json">
    <?php
    echo wp_json_encode( array(
        '@context'        => 'https://schema.org',
        '@type'           => 'NewsMediaOrganization',
        'name'            => 'RINEWS.ID — Media Berita Republik Indonesia',
        'url'             => home_url( '/' ),
        'logo'            => $default_img,
        'publishingPrinciples' => home_url( '/pedoman-media-siber/' ),
        'sameAs'          => array_values( array_filter( array(
            get_theme_mod( 'rinews_facebook_url', '' ),
            get_theme_mod( 'rinews_instagram_url', '' ),
            get_theme_mod( 'rinews_twitter_url', '' ),
            get_theme_mod( 'rinews_youtube_url', '' ),
            get_theme_mod( 'rinews_tiktok_url', '' ),
        ) ) ),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => home_url( '/?s={search_term_string}' ),
            'query-input' => 'required name=search_term_string',
        ),
    ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
    ?>
    </script>
    <?php endif;
}
add_action( 'wp_head', 'rinews_output_social_seo_tags', 5 );
