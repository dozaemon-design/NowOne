<?php
/**
 * Archive Creation
 * 保険のアーカイブテンプレート
 * taxonomy-creation_type.phpが入口となる
*/

get_header();

// 今見ている creation_type を取得
$term = get_queried_object();
$type = ($term && isset($term->slug)) ? $term->slug : 'default';
?>

<main class="l-content p-creation-archive p-creation-archive--<?php echo esc_attr($type); ?>">

  <?php
  /**
   * template-parts/creation/archive/archive-{type}.php
   */
  get_template_part(
    'template-parts/creation/archive/archive',
    $type
  );
  ?>

</main>

<?php get_footer(); ?>