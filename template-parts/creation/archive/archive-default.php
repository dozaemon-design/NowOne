<?php
// アーカイブページは30件表示
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$type = $args['type'] ?? 'default';
$query_args = [
  'post_type'      => 'creation',
  'posts_per_page' => 30,
  'paged'          => $paged,
  'post_status'    => 'publish',
];

// typeが'default'でない場合はcreation_typeでフィルタ
if ($type !== 'default') {
  $query_args['tax_query'] = [
    [
      'taxonomy' => 'creation_type',
      'field'    => 'slug',
      'terms'    => [$type],
    ],
  ];
}
$query = new WP_Query($query_args);
?>

<?php if ($query->have_posts()) : ?>

<ul class="p-creation-list p-creation-list--default">
  <?php while ($query->have_posts()) : $query->the_post(); ?>
    <li class="p-creation-list__item">
      <a href="<?php the_permalink(); ?>" class="p-creation-card">

        <?php if (has_post_thumbnail()) : ?>
          <figure class="p-creation-card__thumb">
            <?php the_post_thumbnail('creation_archive'); ?>
          </figure>
        <?php endif; ?>

        <div class="p-creation-card__body">
          <h2 class="p-creation-card__title">
            <?php the_title(); ?>
          </h2>
        </div>

      </a>
    </li>
  <?php endwhile; ?>
</ul>

<?php 
// ページネーションコンポーネント読み込み
get_template_part('template-parts/creation/component/pagenation', '', [
  'query' => $query,
  'paged' => $paged,
]);
?>

<?php else : ?>
  <p class="c-text">作品がまだありません。</p>
<?php endif; ?>