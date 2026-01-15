<?php
$title = is_front_page()
  ? get_bloginfo('name')
  : single_post_title('', false);

$description = nowone_meta_description();

$url = is_front_page()
  ? 'https://nowone.jp/' // 新規サイト作成時注意
  : get_permalink();

$image = has_post_thumbnail()
  ? get_the_post_thumbnail_url(null, 'full')
  : get_template_directory_uri() . '/assets/img/common/ogp/ogp.png';
?>

<meta property="og:type" content="<?php echo is_front_page() ? 'website' : 'article'; ?>">
<meta property="og:title" content="<?php echo esc_attr($title); ?>">
<meta property="og:description" content="<?php echo esc_attr($description); ?>">
<meta property="og:url" content="<?php echo esc_url($url); ?>">
<meta property="og:image" content="<?php echo esc_url($image); ?>">