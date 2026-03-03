<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="<?php echo esc_attr(nowone_meta_description()); ?>">
<?php get_template_part('template-parts/meta/fonts'); ?>
<?php get_template_part('template-parts/meta/ogp'); ?>
<?php if (defined('GTM_ID')): ?>
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo GTM_ID; ?>');
</script>
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<!-- Google Tag Manager (noscript) -->
 <?php if (defined('GTM_ID')): ?>
<noscript>
<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo GTM_ID; ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<?php endif; ?>
<!-- End Google Tag Manager (noscript) -->

<!-- Portfolio専用ヘッダー -->
<header class="l-header--portfolio">
	<div class="l-header--portfolio__inner">
		<!-- ロゴ -->
			<a class="p-portfolio--title" href="<?php echo home_url('/portfolio'); ?>">
        <?php
        $portfolio_logo_file = get_theme_file_path('/assets/img/portfolio/common/header/portfolio-logo.svg');
        $portfolio_logo_svg = (file_exists($portfolio_logo_file)) ? file_get_contents($portfolio_logo_file) : '';

        if ($portfolio_logo_svg) {
          $portfolio_logo_svg = preg_replace_callback(
            '/<svg\\b[^>]*>/i',
            function ($m) {
              $tag = $m[0];

              if (preg_match('/\\bclass="([^"]*)"/i', $tag, $class_match)) {
                $existing = trim($class_match[1]);
                $classes = preg_split('/\\s+/', $existing) ?: [];
                $classes[] = 'c-portfolio-logo';
                $classes[] = 'c-portfolio-logo--draw';
                $classes = array_values(array_unique(array_filter($classes)));
                $tag = preg_replace('/\\bclass="[^"]*"/i', 'class="' . esc_attr(implode(' ', $classes)) . '"', $tag, 1);
              } else {
                $tag = preg_replace('/<svg\\b/i', '<svg class="c-portfolio-logo c-portfolio-logo--draw"', $tag, 1);
              }

              if (!preg_match('/\\brole="/i', $tag)) {
                $tag = preg_replace('/>$/', ' role="img">', $tag, 1);
              }
              if (!preg_match('/\\baria-label="/i', $tag)) {
                $tag = preg_replace('/>$/', ' aria-label="D.N.Works">', $tag, 1);
              }
              if (!preg_match('/\\bfocusable="/i', $tag)) {
                $tag = preg_replace('/>$/', ' focusable="false">', $tag, 1);
              }

              return $tag;
            },
            $portfolio_logo_svg,
            1
          );

          // SVGエクスポートによっては <path style="fill:..."> 等が入ってCSSアニメが効かないため、描画系をCSS側に寄せる
          $portfolio_logo_svg = preg_replace_callback(
            '/<path\\b[^>]*>/i',
            function ($m) {
              $tag = $m[0];

              $tag = preg_replace('/\\sfill="[^"]*"/i', '', $tag);
              $tag = preg_replace('/\\sstroke="[^"]*"/i', '', $tag);
              $tag = preg_replace('/\\sstroke-width="[^"]*"/i', '', $tag);

              $tag = preg_replace_callback(
                '/\\sstyle="([^"]*)"/i',
                function ($style_match) {
                  $style = (string) $style_match[1];
                  $style = preg_replace('/(?:^|;)\\s*(fill|stroke|stroke-width)\\s*:[^;"]*/i', '', $style);
                  $style = trim($style, "; \t\n\r\0\x0B");
                  if ($style === '') {
                    return '';
                  }
                  return ' style="' . esc_attr($style) . '"';
                },
                $tag
              );

              return $tag;
            },
            $portfolio_logo_svg
          );

          $portfolio_logo_svg = preg_replace('/<path\\b(?![^>]*\\bpathLength=)/i', '<path pathLength="1"', $portfolio_logo_svg);
          echo $portfolio_logo_svg; // theme asset (trusted)
        } else {
          ?>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/portfolio/common/header/portfolio-logo.svg" alt="D.N.Works" width="100%" height="auto">
          <?php
        }
        ?>
			</a>
      <!-- ポートフォリオナビ -->
      <nav
        id="global-nav-portfolio"
        class="c-portfolio-nav"
      >
        <?php
        $portfolio_archive_url = get_post_type_archive_link('portfolio');
        if (!$portfolio_archive_url) {
          $portfolio_archive_url = home_url('/portfolio/');
        }

        $profile_url = home_url('/portfolio/profile/');
        $profile_post = get_page_by_path('profile', OBJECT, 'portfolio');
        if ($profile_post && !is_wp_error($profile_post)) {
          $profile_url = get_permalink($profile_post);
        }

        $contact_url = home_url('/contact/');

        $is_profile = is_singular('portfolio') && (get_post_field('post_name', get_queried_object_id()) === 'profile');
        $is_portfolio_section = (is_post_type_archive('portfolio') || is_tax('portfolio_genre') || is_singular('portfolio'));
        ?>

        <ul class="c-portfolio-nav__primary">
          <li class="c-portfolio-nav__item<?php echo $is_profile ? ' is-current' : ''; ?>">
            <a class="c-portfolio-nav__link" href="<?php echo esc_url($profile_url); ?>" <?php echo $is_profile ? 'aria-current="page"' : ''; ?>>
              PROFILE
            </a>
          </li>
          <li class="c-portfolio-nav__item<?php echo ($is_portfolio_section && !$is_profile) ? ' is-current' : ''; ?>">
            <a class="c-portfolio-nav__link" href="<?php echo esc_url($portfolio_archive_url); ?>" <?php echo ($is_portfolio_section && !$is_profile) ? 'aria-current="page"' : ''; ?>>
              PORTFOLIO
            </a>
          </li>
          <li class="c-portfolio-nav__item">
            <a class="c-portfolio-nav__link" href="<?php echo esc_url($contact_url); ?>">
              CONTACT
            </a>
          </li>
        </ul>

        <?php
        $portfolio_chips = [
          ['type' => 'all', 'label' => 'ALL', 'url' => $portfolio_archive_url],
          ['type' => 'term', 'slug' => 'web', 'label' => 'WEB'],
          ['type' => 'term', 'slug' => 'app', 'label' => 'APP'],
          ['type' => 'term', 'slug' => 'bnr', 'label' => 'BNR'],
          ['type' => 'term', 'slug' => 'publish', 'label' => 'PUBLISH'],
        ];

        $current_term_slug = null;
        if (is_tax('portfolio_genre')) {
          $queried_term = get_queried_object();
          if ($queried_term && !is_wp_error($queried_term) && isset($queried_term->slug)) {
            $current_term_slug = $queried_term->slug;
          }
        }
        ?>
        <div class="c-portfolio-nav__chips js-portfolio-chips" aria-label="Portfolio categories">
          <ul class="c-portfolio-nav__chipsList">
            <?php foreach ($portfolio_chips as $chip) : ?>
              <?php
              $label = $chip['label'];
              $url = $chip['url'] ?? '';
              $is_current = false;

              if (($chip['type'] ?? '') === 'all') {
                $is_current = !is_tax('portfolio_genre');
              } else {
                $slug = $chip['slug'] ?? '';
                $is_current = ($current_term_slug && $slug) ? ($current_term_slug === $slug) : false;

                $term = $slug ? get_term_by('slug', $slug, 'portfolio_genre') : null;
                if (!$term || is_wp_error($term) || !isset($term->count) || (int) $term->count === 0) {
                  continue;
                }
                $url = get_term_link($term);
              }

              if (!$url || is_wp_error($url)) {
                continue;
              }
              ?>
              <li class="c-portfolio-nav__chip<?php echo $is_current ? ' is-current' : ''; ?>">
                <a
                  class="c-portfolio-nav__chipLink"
                  href="<?php echo esc_url($url); ?>"
                  <?php echo $is_current ? 'aria-current="page"' : ''; ?>
                >
                  <?php echo esc_html($label); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </nav>
	</div>
</header>
