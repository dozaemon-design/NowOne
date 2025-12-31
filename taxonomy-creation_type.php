<?php
get_header();

$term = get_queried_object();
$type = $term->slug;
?>

<main class="l-main p-creation-archive p-creation-archive--<?php echo esc_attr($type); ?>">

  <header class="p-creation-archive__header">
    <h1 class="c-heading c-heading--primary">
      <?php single_term_title(); ?>
    </h1>
  </header>

  <?php
  get_template_part(
    'template-parts/creation/archive/archive',
    $type
  );
  ?>

</main>

<?php get_footer(); ?>