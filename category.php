<?php get_header(); ?>
<main>
	<ul class="category_list">
	<?php
$tags = get_tags();
foreach( $tags as $tag) {
echo '<li><a href="'. get_tag_link($tag->term_id) .'">' . $tag->name . '</a></li>';
}
?>
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

<?php get_footer(); ?>