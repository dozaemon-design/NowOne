<?php get_header(); ?>
<div class="contents">
<main>
<ul class="category_list">
<?php
	// 親カテゴリーのものだけを一覧で取得
	$args = array(
		'parent' => 0,
		'orderby' => 'term_order',
		'order' => 'ASC'
	);
	$categories = get_categories( $args );
?>
<?php foreach( $categories as $category ) : ?>
  <li>
    <a href="<?php echo get_category_link( $category->term_id ); ?>"><?php echo $category->name; ?></a>
  </li>
<?php endforeach; ?>
</ul>
<?php if( in_category('movie') ): ?>
<ul class="movie_list">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <li>
      <?php
        $youtube = get_field('youtube_url');
        if( $youtube ) { ?>
       <?php the_field('youtube_url'); ?>
			<?php } ?>
  </li>
<?php endwhile; endif; ?>
</ul>

<?php elseif( in_category('music') ): ?>
	<ul class="music_list">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
  <li>
		<a href="<?php the_field('music_link'); ?>">
      <?php
        $music_thumb = get_field('music_thumb');
        if( $music_thumb ) { ?>
       <img src="<?php the_field('music_thumb'); ?>" alt="">
			<?php } ?>
		</a>
	</li>
<?php endwhile; endif; ?>
</ul>
<?php endif; ?>
</main>
</div>
<?php get_footer(); ?>
