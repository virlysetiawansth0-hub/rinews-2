<?php
/**
 * RINEWS.ID — Template Baca Berita Tunggal (Single Post)
 * Lengkap dengan SEO Schema NewsArticle, Media Share 7 Platform, View Counter, & Komentar.
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
        <div style="display:grid; grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr); gap:18px; align-items:start;">
            <article <?php post_class( 'rn-article-sheet' ); ?>>
                <div class="rn-single-breadcrumbs">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">BERANDA</a> /
                    <?php the_category( ' / ' ); ?>
                </div>

                <h1 class="rn-single-title">
                    <?php the_title(); ?>
                </h1>

                <div class="rn-single-meta">
                    <span>Oleh <strong><?php the_author(); ?></strong> · <?php echo esc_html( get_the_date( 'l, j F Y · H:i' ) ); ?> WIB</span>
                    <span class="rn-single-views"><?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> pembaca</span>
                </div>

                <figure style="margin:0 0 16px;">
                    <?php $single_size = function_exists( 'rinews_get_widget_image_size_slug' ) ? rinews_get_widget_image_size_slug( 'artikel_single' ) : 'rinews-slide'; ?>
                    <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), $single_size ) ); ?>" alt="<?php the_title_attribute(); ?>" class="rn-single-featured-img" />
                    <?php if ( get_the_post_thumbnail_caption() ) : ?>
                        <figcaption class="rn-single-figcaption"><?php the_post_thumbnail_caption(); ?></figcaption>
                    <?php endif; ?>
                </figure>

                <!-- Media Share 7 Platform -->
                <div class="rn-single-share-box">
                    <strong class="rn-single-share-label">Bagikan Berita:</strong>
                    <?php rinews_render_media_share_bar( false, get_permalink(), get_the_title() ); ?>
                </div>

                <div class="rn-article-content">
                    <?php the_content(); ?>
                </div>

                <div class="rn-single-comments-wrap">
                    <?php
                    if ( comments_open() || get_comments_number() ) {
                        comments_template();
                    }
                    ?>
                </div>
            </article>

            <aside style="display:flex; flex-direction:column; gap:14px;">
                <?php
                if ( is_active_sidebar( 'widget-sidebar-artikel' ) ) {
                    dynamic_sidebar( 'widget-sidebar-artikel' );
                } else {
                    the_widget( 'RINEWS_Widget_Berita_Viral', array( 'title' => 'BERITA VIRAL', 'count' => 3 ) );
                    the_widget( 'RINEWS_Widget_Arsip_12_Bulan', array( 'title' => 'ARSIP' ) );
                }
                ?>
            </aside>
        </div>
    <?php endwhile; ?>
</main>

<?php
get_footer();
