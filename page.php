<?php
/**
 * RINEWS.ID — Page Template (Redaksi, Privasi Policy, Tentang Kami, Kontak Iklan)
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
        <article <?php post_class( 'rn-article-sheet' ); ?>>
            <h1 class="rn-page-title">
                <?php the_title(); ?>
            </h1>
            <div class="rn-article-content">
                <?php the_content(); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php
get_footer();
