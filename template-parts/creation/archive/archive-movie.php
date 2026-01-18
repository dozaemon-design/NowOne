<?php
/**
 * Archive Movie Template
 */
?>
<p class="u-visually-hidden">
  NowOne の映像作品一覧。曲を映像に乗せたものを中心に発表しています。見ていただければ幸いです。
</p>
<?php if (have_posts()) : ?>

<ul class="c-creation-list">
<?php while (have_posts()) : the_post(); ?>
  <li class="c-creation-card c-reveal js-reveal">
    <a href="<?php the_permalink(); ?>">
      <figure class="c-creation-card__thumb">
        <?php the_post_thumbnail('creation_thumb'); ?>
      </figure>

      <div class="c-creation-card__body">
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

        <h2 class="c-creation-card__title">
          <?php the_title(); ?>
        </h2>
      </div>
    </a>
    <span class="c-creation-card__border"></span>
  </li>
<?php endwhile; ?>
</ul>

<?php else : ?>
  <p class="c-text">作品がまだありません。</p>
<?php endif; ?>


<?php
// var_dump(is_post_type_archive('movie'));
?>
<?php
// var_dump(is_tax('creation_type', 'movie'));
?>