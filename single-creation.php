<?php
/**
 * Single Creation
 * Router only
 */

get_header();

if (have_posts()) {
  the_post();

  $terms = get_the_terms(get_the_ID(), 'creation_type');
  $type  = (!empty($terms) && !is_wp_error($terms))
    ? $terms[0]->slug
    : 'default';
  ?>

  <main class="l-main p-creation-single">
    <?php
    get_template_part(
      'template-parts/creation/single/single',
      $type
    );
    ?>
  </main>

<?php
}

get_footer();