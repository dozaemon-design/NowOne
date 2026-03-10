<?php
/**
 * Creation Card Component
 */

$title = get_the_title();
$link  = get_permalink();

// ACF thumbnail
$image = get_field('fg_thumb');

$thumb_url = null;
$thumb_alt = $title;
$thumb_fallback_html = '';
$title_tag = $args['title_tag'] ?? 'h2';
if (!in_array($title_tag, ['h2', 'h3'], true)) {
  $title_tag = 'h2';
}

if (is_array($image) && isset($image['sizes']['creation_thumb'])) {
  $thumb_url = $image['sizes']['creation_thumb'];
  $thumb_alt = $image['alt'] ?: $title;
} elseif (has_post_thumbnail()) {
  $thumb_fallback_html = get_the_post_thumbnail(
    get_the_ID(),
    'creation_thumb',
    [
      'loading' => 'lazy',
      'decoding' => 'async',
      'alt' => $title,
    ]
  );
}
?>

<article class="c-creation-card">
  <a href="<?php echo esc_url($link); ?>" class="c-creation-card__link">

    <?php if ($thumb_url || $thumb_fallback_html) : ?>
      <figure class="c-creation-card__thumb">
        <?php if ($thumb_url) : ?>
          <img
            src="<?php echo esc_url($thumb_url); ?>"
            alt="<?php echo esc_attr($thumb_alt); ?>"
            loading="lazy"
            decoding="async"
          >
        <?php else : ?>
          <?php echo $thumb_fallback_html; ?>
        <?php endif; ?>
      </figure>
    <?php endif; ?>
    <div  class="c-creation-card__body">
<?php
        $taxonomies = get_object_taxonomies('creation', 'objects');

        $genre_taxonomies = array_filter($taxonomies, function ($tax) {
          return str_starts_with($tax->name, 'genre_');
        });

        $labels = [];

        foreach ($genre_taxonomies as $taxonomy) {
          $terms = get_the_terms(get_the_ID(), $taxonomy->name);
          if ($terms && !is_wp_error($terms)) {
            $labels = array_merge($labels, $terms);
          }
        }
        ?>

        <?php if ($labels) : ?>
          <ul class="c-creation-card__labels">
            <?php foreach ($labels as $term) : ?>
              <li class="c-creation-card__label">
                <?php echo esc_html($term->name); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>
      <?php
      echo '<' . $title_tag . ' class="c-creation-card__title">'
        . esc_html($title)
        . '</' . $title_tag . '>';
      ?>
    </div>
  </a>
  <span class="c-creation-card__border"></span>
</article>
