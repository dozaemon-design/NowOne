<?php get_template_part('template-parts/layout/header-portfolio'); ?>

<main class="l-main p-portfolio-page">
  <section class="l-content l-content--inline">
    <h1 class="p-portfolio-page__title">
      <?php the_title(); ?>
    </h1>
    <?php
    while (have_posts()) :
      the_post();
      the_content();
    endwhile;
    ?>
  </section>
</main>

<?php get_template_part('template-parts/layout/footer-portfolio'); ?>

