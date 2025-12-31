<?php
/**
 * archive-portfolio.php
 * portfolio 投稿タイプの一覧（ログイン必須）
 */
get_header();

// 追加のチェックは functions.php の restrict_portfolio_access が行う前提
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$q = new WP_Query(['post_type'=>'portfolio','posts_per_page'=>12,'paged'=>$paged]);
?>

<main class="portfolio-archive">
    <h1>Portfolio（実績）</h1>

    <?php if ($q->have_posts()) : ?>
        <?php while ($q->have_posts()) : $q->the_post(); ?>
            <article>
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) the_post_thumbnail('music_thumb'); ?>
                    <h2><?php the_title(); ?></h2>
                </a>
            </article>
        <?php endwhile; ?>
        <?php echo paginate_links(['total'=>$q->max_num_pages]); ?>
    <?php else: ?>
        <p>実績はありません。</p>
    <?php endif; wp_reset_postdata(); ?>
</main>

<?php get_footer(); ?>