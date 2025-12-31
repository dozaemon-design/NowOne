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

if (is_array($image) && isset($image['sizes']['creation_thumb'])) {
  $thumb_url = $image['sizes']['creation_thumb'];
  $thumb_alt = $image['alt'] ?: $title;
}
?>

<article class="c-creation-card">
  <a href="<?php echo esc_url($link); ?>" class="c-creation-card__link">

    <?php if ($thumb_url) : ?>
      <figure class="c-creation-card__thumb">
        <img
          src="<?php echo esc_url($thumb_url); ?>"
          alt="<?php echo esc_attr($thumb_alt); ?>"
          loading="lazy"
        >
      </figure>
    <?php endif; ?>

    <h2 class="c-creation-card__title">
      <?php echo esc_html($title); ?>
    </h2>

  </a>
</article>