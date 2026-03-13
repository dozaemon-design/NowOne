<?php
/**
 * Creation Archive List (shared)
 * - archive-music/movie/artwork から呼び出し
 * - archive.php（fallback）からも呼び出し
 */

// アーカイブページは30件表示
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$type = $args['type'] ?? '';

$query_args = [
  'post_type'      => 'creation',
  'posts_per_page' => 30,
  'paged'          => $paged,
  'post_status'    => 'publish',
];

if ($type !== '') {
  $query_args['tax_query'] = [[
    'taxonomy' => 'creation_type',
    'field'    => 'slug',
    'terms'    => [$type],
  ]];
}

$query = new WP_Query($query_args);
?>

<?php if ($query->have_posts()) : ?>

<ul class="c-creation-list">
<?php while ($query->have_posts()) : $query->the_post(); ?>
  <li class="c-reveal js-reveal">
    <?php
    $is_lcp = ($paged === 1 && $query->current_post === 0);
    get_template_part('template-parts/creation/component/card', '', [
      'is_lcp' => $is_lcp,
    ]);
    ?>
  </li>
<?php endwhile; ?>
</ul>

<?php
get_template_part('template-parts/creation/component/pagenation', '', [
  'query' => $query,
  'paged' => $paged,
]);
?>

<?php else : ?>
  <p class="c-text">作品がまだありません。</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
