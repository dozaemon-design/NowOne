<?php
/**
 * Creation Card Component
 */

$title = get_the_title();
$link  = get_permalink();

// ACF thumbnail
$image = get_field('fg_thumb');

$thumb_html = '';
$title_tag = $args['title_tag'] ?? 'h2';
$is_lcp = (bool) ($args['is_lcp'] ?? false);
$img_loading = $is_lcp ? 'eager' : 'lazy';
$img_fetchpriority = $is_lcp ? 'high' : '';
if (!in_array($title_tag, ['h2', 'h3'], true)) {
  $title_tag = 'h2';
}

$thumb_attrs = [
  'loading' => $img_loading,
  'decoding' => 'async',
  'sizes' => '(max-width: 768px) 100vw, 33vw',
];
if ($img_fetchpriority) {
  $thumb_attrs['fetchpriority'] = $img_fetchpriority;
}

$acf_attachment_id = function_exists('nowone_get_attachment_id_from_acf')
  ? nowone_get_attachment_id_from_acf($image)
  : (is_array($image) ? (int) ($image['ID'] ?? 0) : 0);

if ($acf_attachment_id && wp_attachment_is_image($acf_attachment_id)) {
  // ACF優先：IDベースで出力（WebP/AVIF等はプラグイン/フィルタに委譲）
  $thumb_html = wp_get_attachment_image($acf_attachment_id, 'creation_thumb', false, $thumb_attrs);
} elseif (has_post_thumbnail()) {
  $thumb_html = get_the_post_thumbnail(get_the_ID(), 'creation_thumb', $thumb_attrs);
}
?>

<article class="c-creation-card">
  <a href="<?php echo esc_url($link); ?>" class="c-creation-card__link">

    <?php if ($thumb_html) : ?>
      <figure class="c-creation-card__thumb">
        <?php echo $thumb_html; ?>
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
