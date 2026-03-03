<?php get_template_part('template-parts/layout/header-portfolio'); ?>

<main class="l-main--portfolio">

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <?php
    $slug = get_post_field('post_name', get_the_ID());
    $slug = $slug ? sanitize_title($slug) : '';

    $custom_template = $slug
      ? locate_template("template-parts/portfolio/single/single-{$slug}.php", false, false)
      : '';

    if ($custom_template) {
      get_template_part('template-parts/portfolio/single/single', $slug);
    } else {
      get_template_part('template-parts/portfolio/single/single', 'portfolio');
    }
    ?>

  <?php endwhile; ?>
<?php endif; ?>
</main>

<?php get_template_part('template-parts/layout/footer-portfolio'); ?>
