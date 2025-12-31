<?php
/**
 * Archive Creation
 */

get_header();

// 今見ている creation_type を取得
$term = get_queried_object();
$type = ($term && isset($term->slug)) ? $term->slug : 'default';
?>

<main class="l-main p-creation-archive p-creation-archive--<?php echo esc_attr($type); ?>">

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