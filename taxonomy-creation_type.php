<?php
get_header();

$term = get_queried_object();
$type = $term->slug;
?>

<main class="l-main">
  <section class="l-content l-content--inline">
      <h1 class="c-heading-2xl">
        <?php single_term_title(); ?> - Discography
      </h1>
      <?php
      get_template_part(
        'template-parts/creation/archive/archive',
        $type
      );
      ?>
  </section>
</main>
</div> <?php //div end ?>
<?php get_footer(); ?>