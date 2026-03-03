<?php get_template_part('template-parts/layout/header-portfolio'); ?>

<main class="l-main p-portfolio-profile">
  <section class="l-content l-content--inline">
    <h1 class="p-portfolio-profile__title">
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
