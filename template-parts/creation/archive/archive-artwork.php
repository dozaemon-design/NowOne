<?php if (have_posts()) : ?>

<ul class="p-creation-list p-creation-list--artwork">
  <?php while (have_posts()) : the_post(); ?>
    <li class="p-creation-list__item">
      <a href="<?php the_permalink(); ?>" class="p-creation-card">

        <?php if (has_post_thumbnail()) : ?>
          <figure class="p-creation-card__thumb">
            <?php the_post_thumbnail('creation_archive'); ?>
          </figure>
        <?php endif; ?>

        <div class="p-creation-card__body">
          <h2 class="p-creation-card__title">
            <?php the_title(); ?>
          </h2>
        </div>

      </a>
    </li>
  <?php endwhile; ?>
</ul>

<?php else : ?>
  <p class="c-text">作品がまだありません。</p>
<?php endif; ?>