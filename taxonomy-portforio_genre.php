<?php get_header(); ?>

<main class="l-main p-portfolio-archive">

  <h1 class="p-portfolio-archive__title">
    <?php single_term_title(); ?>
  </h1>

  <?php if (have_posts()) : ?>
    <ul class="p-portfolio-list">
      <?php while (have_posts()) : the_post(); ?>
        <li class="p-portfolio-list__item">
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail('medium'); ?>
            <h2><?php the_title(); ?></h2>
          </a>
        </li>
      <?php endwhile; ?>
    </ul>
  <?php endif; ?>

</main>

<?php get_footer(); ?>