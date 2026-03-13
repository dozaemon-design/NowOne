<?php get_template_part('template-parts/layout/header-portfolio'); ?>

<main class="l-main--portfolio p-portfolio-archive">

  <h1 class="p-portfolio-archive__title">Portfolio</h1>

  <?php if (have_posts()) : ?>
    <ul class="p-portfolio-list">
      <?php while (have_posts()) : the_post(); ?>
        <li class="l-grid p-portfolio-list__item c-hover-invert c-hover-zoom c-reveal js-reveal">
          <a class="l-cluster p-portfolio-list__link" href="<?php the_permalink(); ?>">
            <div class="p-portfolio-list__thumb">
              <?php
              global $wp_query;
              $is_lcp = (!is_paged() && $wp_query instanceof WP_Query && $wp_query->current_post === 0);
              $thumb_attrs = [
                'data-hover-zoom' => 'img',
                'loading'         => $is_lcp ? 'eager' : 'lazy',
                'decoding'        => 'async',
              ];
              if ($is_lcp) {
                $thumb_attrs['fetchpriority'] = 'high';
              }
              echo get_the_post_thumbnail(get_the_ID(), 'portfolio_thumb', $thumb_attrs);
              ?>
            </div>
            <div class="p-portfolio-list__body">
              <h2 class="p-portfolio-list__title">
                <?php the_title(); ?>
              </h2>

              <?php
              $post_id = get_the_ID();
              $role_terms = get_the_terms($post_id, 'portfolio_role');

              if ((empty($role_terms) || is_wp_error($role_terms)) && function_exists('get_field')) {
                $acf_role_value = get_field('portfolio_role', $post_id);

                if (is_array($acf_role_value)) {
                  $normalized_terms = [];
                  foreach ($acf_role_value as $maybe_term) {
                    if (is_object($maybe_term) && isset($maybe_term->term_id)) {
                      $normalized_terms[] = $maybe_term;
                      continue;
                    }

                    $term_id = is_numeric($maybe_term) ? (int) $maybe_term : 0;
                    if ($term_id) {
                      $term = get_term($term_id, 'portfolio_role');
                      if ($term && !is_wp_error($term)) {
                        $normalized_terms[] = $term;
                      }
                    }
                  }
                  $role_terms = $normalized_terms;
                }
              }
              ?>
              <!-- 役割 -->
              <?php if (!empty($role_terms) && !is_wp_error($role_terms)) : ?>
                <ul class="l-cluster p-portfolio-list__roles">
                  <?php foreach ($role_terms as $term) : ?>
                    <li class="p-portfolio-list__role" data-hover-invert="chip">
                      <?php echo esc_html($term->name); ?>
                    </li>
                  <?php endforeach; ?>
              </ul>
              <?php endif; ?>

              <?php
              $raw_start_date = function_exists('get_field') ? get_field('portfolio_start_date', $post_id) : '';
              $year = '';
              $datetime = '';

              if (!empty($raw_start_date)) {
                $raw = (string) $raw_start_date;
                if (ctype_digit($raw) && strlen($raw) >= 4) {
                  $year = substr($raw, 0, 4);
                  if (strlen($raw) >= 8) {
                    $datetime = substr($raw, 0, 4) . '-' . substr($raw, 4, 2) . '-' . substr($raw, 6, 2);
                  }
                } else {
                  $ts = strtotime($raw);
                  if ($ts) {
                    $year = date('Y', $ts);
                    $datetime = date('Y-m-d', $ts);
                  }
                }
              } else {
                $year = get_the_date('Y');
                $datetime = get_the_date('Y-m-d');
              }
              ?>
              <?php if (!empty($year)) : ?>
                <time class="p-portfolio-list__time" data-hover-invert="badge" datetime="<?php echo esc_attr($datetime ?: ($year . '-01-01')); ?>">
                  <?php echo esc_html($year); ?>
                </time>
              <?php endif; ?>
            </div>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php endif; ?>

</main>

<?php get_template_part('template-parts/layout/footer-portfolio'); ?>
