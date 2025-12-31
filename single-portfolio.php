<?php get_header(); ?>

<main class="l-main">

<?php if (have_posts()) : ?>
  <?php while (have_posts()) : the_post(); ?>

    <article class="p-portfolio-single">

      <h1><?php the_title(); ?></h1>

      <?php if (has_post_thumbnail()) : ?>
        <?php the_post_thumbnail('large'); ?>
      <?php endif; ?>

      <?php if (get_field('portfolio_description')) : ?>
        <p><?php the_field('portfolio_description'); ?></p>
      <?php endif; ?>

      <ul class="p-portfolio-meta">
        <?php if (get_field('portfolio_period')) : ?>
          <li>制作期間：<?php the_field('portfolio_period'); ?></li>
        <?php endif; ?>

        <?php if (get_field('portfolio_role')) : ?>
          <li>担当：<?php the_field('portfolio_role'); ?></li>
        <?php endif; ?>

        <?php if (get_field('portfolio_tools')) : ?>
          <li>使用技術：<?php the_field('portfolio_tools'); ?></li>
        <?php endif; ?>

        <?php if (get_field('portfolio_link')) : ?>
          <li>
            <a href="<?php the_field('portfolio_link'); ?>" target="_blank" rel="noopener">
              外部リンク
            </a>
          </li>
        <?php endif; ?>
      </ul>

    </article>

  <?php endwhile; ?>
<?php endif; ?>

</main>

<?php get_footer(); ?>