<?php
/**
 * Single Music Template
 */

$title = get_the_title();

$detail_image = get_field('creation_thumbnail'); // 統一
$bpm  = get_field('music_bpm');
$key  = get_field('music_key');
$time = get_field('music_time');
$url  = get_field('music_url');
$desc = get_field('music_description');
?>
<!-- SINGLE DEFAULT TEMPLATE LOADED -->
<section class="p-music">

  <?php if ($detail_image && isset($detail_image['sizes']['creation_detail'])): ?>
    <figure class="p-music__hero">
      <img
        src="<?php echo esc_url($detail_image['sizes']['creation_detail']); ?>"
        alt="<?php echo esc_attr($detail_image['alt'] ?: $title); ?>"
        loading="lazy"
      >
    </figure>
  <?php endif; ?>

  <h1 class="c-heading c-heading--primary">
    <?php echo esc_html($title); ?>
  </h1>

  <?php if ($bpm || $key || $time): ?>
    <ul class="p-music__meta c-text">
      <?php if ($bpm): ?><li>BPM: <?php echo esc_html($bpm); ?></li><?php endif; ?>
      <?php if ($key): ?><li>Key: <?php echo esc_html($key); ?></li><?php endif; ?>
      <?php if ($time): ?><li>Time: <?php echo esc_html($time); ?></li><?php endif; ?>
    </ul>
  <?php endif; ?>

  <?php if ($desc): ?>
    <div class="p-music__description c-text">
      <?php echo wp_kses_post($desc); ?>
    </div>
  <?php endif; ?>

  <?php if ($url): ?>
    <p class="p-music__link">
      <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">
        Listen
      </a>
    </p>
  <?php endif; ?>

</section>