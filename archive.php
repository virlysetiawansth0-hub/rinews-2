<?php
/**
 * RINEWS.ID — Archive / Category / Search Template
 *
 * @package RINEWS
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>

<main class="rn-container rn-section">
    <div class="rn-red-panel">
        <div class="rn-panel-header">
            <h1 class="rn-panel-title">
                <?php
                if ( is_search() ) {
                    echo 'HASIL PENCARIAN: ' . esc_html( get_search_query( false ) );
                } elseif ( is_category() ) {
                    echo 'KANAL ' . esc_html( single_cat_title( '', false ) );
                } else {
                    echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
                }
                ?>
            </h1>
            <a class="rn-panel-more" href="<?php echo esc_url( home_url( '/' ) ); ?>">&larr; Kembali ke Beranda</a>
        </div>

        <?php if ( have_posts() ) : ?>
            <div class="rn-3col-grid">
                <?php while ( have_posts() ) : the_post(); ?>
                    <a href="<?php the_permalink(); ?>" class="rn-news-card">
                        <img src="<?php echo esc_url( rinews_get_post_image_url( get_the_ID(), 'rinews-card' ) ); ?>" alt="<?php the_title_attribute(); ?>" />
                        <div class="rn-news-card-body">
                            <div>
                                <div class="rn-card-cat">
                                    <?php $c = get_the_category(); echo ! empty( $c ) ? esc_html( $c[0]->name ) : 'BERITA'; ?>
                                </div>
                                <h3><?php the_title(); ?></h3>
                            </div>
                            <div class="rn-meta-line">
                                <?php echo esc_html( get_the_date( 'd M Y' ) ); ?> · <?php echo esc_html( number_format_i18n( rinews_get_post_views() ) ); ?> dibaca
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </div>
            <div style="margin-top:16px; text-align:center; color:#FFF; font-weight:800;">
                <?php the_posts_pagination(); ?>
            </div>
        <?php else : ?>
            <div class="rn-white-box" style="padding:30px; text-align:center;">
                <p>Belum ada berita pada arsip atau pencarian ini.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
