<?php get_header(); ?>
<main>
<?php if( in_category('movie') ): ?>
	<ul class="category_list">
  <?php
$tag = get_the_tags();
echo '<li><a href="'. get_category_link($tag[0]->term_id) .'">' . $tag[0]->name . '</a></li>';
?>
	</ul>
  <ul class="music_list">
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
	<ul class="category_list">
  <?php
$tag = get_the_tags();
echo '<li><a href="'. get_category_link($tag[0]->term_id) .'">' . $tag[0]->name . '</a></li>';
?>
	</ul>
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

<?php get_footer(); ?>