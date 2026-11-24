<?php
/**
 * Template Name: RINEWS Full-Width (Elementor / Page Builder / Toko)
 * Template Post Type: page, post
 *
 * Kompatibel penuh dengan WordPress 7.1, Elementor, WPBakery, Gutenberg Block Editor,
 * WooCommerce, Contact Form 7, WPForms, dan halaman dari website lama.
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>

<main class="rn-container rn-section">
    <?php while ( have_posts() ) : the_post(); ?>
        <div <?php post_class( 'rn-builder-wrapper rn-article-sheet' ); ?>>
            <?php the_content(); ?>
        </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
