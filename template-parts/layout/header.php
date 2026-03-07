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

<?php
$disable_page_transition = (
  is_singular('portfolio')
  || is_post_type_archive('portfolio')
  || is_tax('portfolio_genre')
  || is_page(['contact', 'contact-thanks'])
);
?>
<?php if (!$disable_page_transition) : ?>
  <?php get_template_part('template-parts/layout/page-transition'); ?>
<?php endif; ?>

<div class="l-wrap">
<!-- aria属性改訂版 -->
<header class="l-header">
	<div class="l-header__inner">
		<!-- ロゴ -->
		<div class="c-site-logo">
			<a href="<?php echo home_url('/'); ?>">
        <?php
        $site_logo_file = get_theme_file_path('/assets/img/common/logo.svg');
        $site_logo_svg = (file_exists($site_logo_file)) ? file_get_contents($site_logo_file) : '';

        if ($site_logo_svg) {
          $site_logo_svg = preg_replace_callback(
            '/<svg\\b[^>]*>/i',
            function ($m) {
              $tag = $m[0];

              if (preg_match('/\\bclass="([^"]*)"/i', $tag, $class_match)) {
                $existing = trim($class_match[1]);
                $classes = preg_split('/\\s+/', $existing) ?: [];
                $classes[] = 'c-site-logo__svg';
                $classes[] = 'c-site-logo__svg--draw';
                $classes = array_values(array_unique(array_filter($classes)));
                $tag = preg_replace('/\\bclass="[^"]*"/i', 'class="' . esc_attr(implode(' ', $classes)) . '"', $tag, 1);
              } else {
                $tag = preg_replace('/<svg\\b/i', '<svg class="c-site-logo__svg c-site-logo__svg--draw"', $tag, 1);
              }

              if (!preg_match('/\\brole="/i', $tag)) {
                $tag = preg_replace('/>$/', ' role="img">', $tag, 1);
              }
              if (!preg_match('/\\baria-label="/i', $tag)) {
                $tag = preg_replace('/>$/', ' aria-label="NowOne">', $tag, 1);
              }
              if (!preg_match('/\\bfocusable="/i', $tag)) {
                $tag = preg_replace('/>$/', ' focusable="false">', $tag, 1);
              }

              return $tag;
            },
            $site_logo_svg,
            1
          );

          $site_logo_svg = preg_replace('/<path\\b(?![^>]*\\bpathLength=)/i', '<path pathLength="1"', $site_logo_svg);
          echo $site_logo_svg; // theme asset (trusted)
        } else {
          ?>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="NowOne">
          <?php
        }
        ?>
			</a>
		</div>
		<!-- メニューボタン -->
		<button
				class="c-menu-toggle"
				aria-label="メニューを開く"
				aria-expanded="false"
				aria-controls="global-nav"
			>
			<span class="c-menu-toggle__line"></span>
		</button>
			<!-- グローバルナビ -->
			<nav
				id="global-nav"
				class="l-global-nav c-global-nav"
				aria-hidden="true"
			>
				<?php get_template_part('template-parts/creation/component/global-nav'); ?>
			</nav>
	</div>

</header>
