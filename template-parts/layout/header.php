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

<div class="l-main">
<!-- aria属性改訂版 -->
<header class="l-header c-global-nav">
	<div class="l-header__inner">
		<!-- ロゴ -->
		<div class="c-site-logo">
			<a href="<?php echo home_url('/'); ?>">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/img/common/logo.svg" alt="NowOne">
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
				class="c-global-nav p-global-nav"
				aria-hidden="true"
			>
				<?php get_template_part('template-parts/creation/global-nav'); ?>
			</nav>
	</div>

</header>