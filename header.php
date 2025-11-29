<!DOCTYPE html>
<html lang="jp">
<head>
<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>;charset=<?php bloginfo ('charset'); ?>" />
<meta name="Description" content="ウェブ制作者NowOne.jpの研究サイトです。" />
<meta name="keywords" content="webdesigner,ウェブ制作者,music" />

<?php if(is_home()): ?>
<title><?php bloginfo('name'); ?></title>
<?php else: ?>
<title><?php the_title(); ?></title>
<?php endif; ?>

<link rel="stylesheet" href="<?php bloginfo ('stylesheet_url'); ?>" type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS_FEED" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="ATOM_FEED" href="<?php bloginfo('atom_url'); ?>" />


<!-- web fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Big+Shoulders+Text:wght@100;400&display=swap" rel="stylesheet">
<!-- web fonts -->
<!-- favicon -->
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/favicon.ico"/>
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/favicon-16x16.png">
<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/site.webmanifest">
<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/images/common/favicon/safari-pinned-tab.svg" color="#5bbad5">
<!-- favicon -->
<!-- js -->
<script src="<?php echo get_template_directory_uri(); ?>/js/lib/jquery-3.6.0.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/lib/jquery.easing.1.3.js" type="text/javascript"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/common.js" type="text/javascript"></script>
<!-- js -->
<!-- ▼分析用▼ -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-0ZY224QMDN"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-0ZY224QMDN');
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-KFC2KQX');</script>
<!-- End Google Tag Manager -->
<!-- ▲分析用▲ -->

<!-- [head]内や、[body]の終了直前などに配置 -->
<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<?php if(is_home()): ?>
<meta property="og:site_name" content="Nowone.jp" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php bloginfo('template_url'); ?>" />
<meta property="og:title" content="Nowone.jp" />
<meta property="og:image" content="<?php bloginfo('template_url'); ?>/img/common/sns_image.jpg" />
<meta property="og:description" content="ウェブデザイナーnowone.jpの研究サイトです。" />
<?php else: ?>
<meta property="og:site_name" content="Nowone.jp" />
<meta property="og:type" content="website" />
<meta property="og:url" content="<?php the_permalink() ?>" />
<meta property="og:title" content="<?php the_title(); ?> | NowOne" />
<meta property="og:image" content="" />
<meta property="og:description" content="ウェブデザイナーnowone.jpの研究サイトです。" />
<?php endif; ?>
<?php wp_deregister_script('jquery'); ?>
<?php wp_head(); ?>
</head>
<body <?php $body_id = $body_id ?? ''; ?> >
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KFC2KQX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<header>
	<h1><img src="<?php bloginfo('template_url'); ?>/images/common/logo.png" alt="NowOne"></h1>
</header>
<div class="hmenu">
	<input type="checkbox" id="menu-btn-check">
	<label for="menu-btn-check" class="menu-btn"><span></span></label>
	<nav id="gnav">
		<ul class="gnav_list">
			<li><a href="<?php echo home_url( '/' ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_lion.svg" alt="">HOME</a></li>
			<li><a href="<?php echo home_url( '/music' ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_category.svg" alt="">MUSIC</a></li>
			<li><a href="<?php echo home_url( '/movie' ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_movie.svg" alt="">MOVIE</a></li>
			<li><a href="<?php echo home_url( '/profile' ); ?>"><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_profile.svg" alt="">PROFILE</a></li>
			<!-- <li><a href=""><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_blog.svg" alt="">BLOG</a></li> -->
			<!-- <li><a href=""><img src="<?php bloginfo('template_url'); ?>/images/common/header/icon_contact.svg" alt="">CONTACT</a></li> -->
		</ul>
		<ul class="sns">
			<li><a href="https://twitter.com/nowone_though" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/common/sns/twitter.svg" alt="twitter"></a></li>
			<li><a href="https://www.instagram.com/nowone.design/" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/common/sns/instagram.svg" alt="instagram"></a></li>
			<li><a href="https://www.youtube.com/channel/UCAKLjbh08XL4EX5V4jg5Nig/featured" target="_blank"><img src="<?php bloginfo('template_url'); ?>/images/common/sns/youtube.svg" alt="youtube"></a></li>
		</ul>
	</nav>
</div>