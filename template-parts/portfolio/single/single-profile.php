<article class="p-portfolio-profile">

  <h1 class="p-portfolio-profile__title"><?php the_title(); ?></h1>
  <?php if (has_post_thumbnail()) : ?>
    <figure class="p-portfolio-profile__thumb">
      <?php the_post_thumbnail('large'); ?>
    </figure>
  <?php endif; ?>

  <div class="p-portfolio-profile__content">
    <?php the_content(); ?>
  </div>

</article>
