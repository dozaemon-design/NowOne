<?php
/**
 * Recommend (Creation)
 * - 最大5件
 * - 単純統一：表示中の記事と同じ genre_* の同一カテゴリ（term）から取得
 * - 表示中の記事は除外
 */

$post_id = get_the_ID();
if (!$post_id) {
  return;
}

$genre_taxonomy = '';
if (has_term('', 'genre_music', $post_id)) {
  $genre_taxonomy = 'genre_music';
} elseif (has_term('', 'genre_movie', $post_id)) {
  $genre_taxonomy = 'genre_movie';
} elseif (has_term('', 'genre_artwork', $post_id)) {
  $genre_taxonomy = 'genre_artwork';
}

if ($genre_taxonomy === '') {
  return;
}

$genre_term_ids = wp_get_post_terms($post_id, $genre_taxonomy, ['fields' => 'ids']);
if (is_wp_error($genre_term_ids) || empty($genre_term_ids)) {
  return;
}

$recommend_query = new WP_Query([
  'post_type' => 'creation',
  'post_status' => 'publish',
  'posts_per_page' => 5,
  'post__not_in' => [$post_id],
  'ignore_sticky_posts' => true,
  'no_found_rows' => true,
  'orderby' => 'date',
  'order' => 'DESC',
  'tax_query' => [[
    'taxonomy' => $genre_taxonomy,
    'field' => 'term_id',
    'terms' => $genre_term_ids,
  ]],
]);

if (!$recommend_query->have_posts()) {
  return;
}
?>

<article class="l-content l-content--inline c-recommend">
  <h2 class="c-recommend__title">RECOMMEND</h2>
  <ul class="c-recommend__list c-creation-list">
    <?php while ($recommend_query->have_posts()) : ?>
      <?php $recommend_query->the_post(); ?>
      <li class="c-recommend__item c-reveal js-reveal">
        <?php get_template_part('template-parts/creation/component/card', '', ['title_tag' => 'h3']); ?>
      </li>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  </ul>
</article>
