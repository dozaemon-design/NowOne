<?php
	get_header();

	$term = get_queried_object();
	$type = ($term && isset($term->slug)) ? $term->slug : 'default';
	?>

	<main class="l-main">
	  <section class="l-content l-content--inline">
	      <h1 class="c-heading-2xl">
	        <?php
	        $label_map = [
	          'music' => 'Music',
	          'movie' => 'Movie',
	          'artwork' => 'Artwork',
	        ];
	        $suffix_map = [
	          'music' => 'Discography',
	          'movie' => 'Videography',
	          'artwork' => 'Gallery',
	        ];
	        $term_title = single_term_title('', false);
	        $label = $label_map[$type] ?? $term_title;
	        echo esc_html($label);
	        $suffix = $suffix_map[$type] ?? 'Discography';
	        ?> - <?php echo esc_html($suffix); ?>
	      </h1>
		      <?php
		      get_template_part(
		        'template-parts/creation/archive/archive',
	        $type,
	        ['type' => $type]
	      );
	      ?>
	  </section>
	</main>
</div> <?php //div end ?>
<?php get_footer(); ?>
