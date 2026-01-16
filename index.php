<?php get_header(); ?>
<?php // 各creationタイプの最新10件を取得
$args = [
  'post_type'      => 'creation',
  'posts_per_page' => 10,
  'post_status'    => 'publish',
  'tax_query'      => [
    [
      'taxonomy' => 'creation_type',
      'field'    => 'slug',
      'terms'    => ['music', 'movie', 'artwork'],
    ],
  ],
  'no_found_rows'  => true, // ← 重要（ページングしない）
];

$creations = new WP_Query($args);
?>
<main class="l-main">

  <section class="p-home">
    <canvas id="bg-lines"></canvas>
    <div class="p-home__titleWrap">
      <h1 class="p-home__title js-split" data-splitting>
        いま、<br>
        創りたいものを創る。<br>
        ただ、それだけ。
      </h1>
    </div>
  </section>

  <section class="l-content l-content--inline">
    <h2 class="c-heading--xl">New Creations</h2>
      <ul class="c-creation-list">
        <?php while ($creations->have_posts()) : $creations->the_post(); ?>
          <li class="c-creation-card c-reveal js-reveal">
            <a href="<?php the_permalink(); ?>">
              <figure class="c-creation-card__thumb">
                <?php the_post_thumbnail('creation_thumb'); ?>
              </figure>
              <div class="c-creation-card__body">
                <?php
                $type_terms = get_the_terms(get_the_ID(), 'creation_type');
                ?>
                <?php if ($type_terms && !is_wp_error($type_terms)) : ?>
                  <ul class="c-creation-card__labels">
                    <?php foreach ($type_terms as $term) : ?>
                      <li class="c-title--s c-creation-card__label">
                        <?php echo esc_html($term->name); ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                <?php endif; ?>
                <h3 class="c-creation-card__title">
                  <?php the_title(); ?>
                </h3>
              </div>
            </a>
            <span class="c-creation-card__border"></span>
          </li>
        <?php endwhile; ?>
      </ul>
<?php
// var_dump(
//   taxonomy_exists('creation_type'),
//   is_object_in_taxonomy('creation', 'creation_type')
// );

?>
    </section>

</main>

<?php get_footer(); ?>

<?php
echo __DIR__;