<?php
/**
 * Single Creation
 */
/**
 * NOTE:
 * 現在は Page 経由で creation を表示するため未使用
 */

get_header();

if (have_posts()) :
  while (have_posts()) : the_post();

    // creation_type を取得
    $terms = get_the_terms(get_the_ID(), 'creation_type');
    $type  = (!empty($terms) && !is_wp_error($terms))
      ? $terms[0]->slug
      : 'default';
    ?>
<pre>
<!-- <?php
var_dump($terms);
var_dump($type);
?> -->
</pre>
    <main class="l-main p-creation-single p-creation-single--<?php echo esc_attr($type); ?>">

      <?php
      /**
       * 表示テンプレート切り替え
       * template-parts/creation/single-{type}.php
       */
      get_template_part(
        'template-parts/creation/single/single',
        $type
      );
      ?>

    </main>

    <?php
  endwhile;
endif;

get_footer();