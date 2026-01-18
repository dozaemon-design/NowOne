<?php
/**
 * Theme bootstrap
 */
$inc_files = [
    'acf.php',
    'helpers-image.php',
    'post-types.php',
    'seed.php',
    'image-sizes.php',
    'admin.php',
    'admin-columns.php',
    'enqueue.php',
];

foreach ($inc_files as $file) {
    $path = get_template_directory() . '/inc/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}

/**
 * Enable title tag support
 */
add_theme_support('title-tag');

/**
 * Generate meta description (NowOne final)
 */
function nowone_meta_description() {

  // フロント
  if (is_front_page()) {
    return 'NowOneの公式サイト。音楽・映像・デザイン制作。';
  }

  // singular（ACF前提）
  if (is_singular('creation')) {
    $post_id = get_queried_object_id();
    if (!$post_id) {
      return '';
    }

    // ① ACF 明示指定
    if (function_exists('get_field')) {
      $acf_meta = get_field('creation_meta_description', $post_id);
      if (!empty($acf_meta)) {
        return wp_strip_all_tags($acf_meta);
      }
    }

    // ② fallback：タイトル + tax
    $title = get_the_title($post_id);
    $terms = wp_get_post_terms($post_id, 'creation_type');
    $suffix = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';

    return trim($title . '｜' . $suffix . '｜NowOne');
  }

  // taxonomy
  if (is_tax() || is_category() || is_tag()) {
    $term = get_queried_object();
    if (!empty($term->description)) {
      return wp_strip_all_tags($term->description);
    }
    return $term->name . '一覧｜NowOne';
  }

  return get_bloginfo('description');
}

/**
 * favicon support
 */
add_action('wp_head', function () {
  $dir = get_template_directory_uri() . '/assets/img/common/favicon';

  echo <<<HTML
<link rel="icon" href="{$dir}/favicon.svg" type="image/svg+xml">
<link rel="icon" href="{$dir}/favicon.ico" sizes="any">
<link rel="apple-touch-icon" href="{$dir}/apple-touch-icon.png">
<link rel="manifest" href="{$dir}/site.webmanifest">
HTML;
});

/**
 * 現在のグローバルナビ判定
 *
 * @param string $type home | music | movie など
 * @return bool
 */
function nowone_is_current_nav($type) {
  if ($type === 'home') {
    return is_front_page() || is_home();
  }

  $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  $segments = explode('/', $request);

  return isset($segments[0]) && $segments[0] === $type;
}

function nowone_get_current_segment() {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  return explode('/', $path)[0] ?? '';
}

/**
 * Creation Single パーマリンク構造のカスタマイズ
 */
add_filter('post_type_link', function ($link, $post) {
    if ($post->post_type !== 'creation') return $link;

    $terms = get_the_terms($post->ID, 'creation_type');
    if (empty($terms) || is_wp_error($terms)) return $link;

    return home_url('/' . $terms[0]->slug . '/' . $post->post_name . '/');
}, 10, 2);


/**
 * Creation Single 用のリライトルール追加
 */
add_action('init', function () {

    // /music/slug/ → creation の single
    add_rewrite_rule(
        '^(music|movie|artwork)/([^/]+)/?$',
        'index.php?post_type=creation&name=$matches[2]',
        'top'
    );

});

/**
 * Creation アーカイブ パーマリンク構造のカスタマイズ
 */

add_filter('term_link', function ($url, $term, $taxonomy) {
    if ($taxonomy === 'creation_type') {
        return home_url('/' . $term->slug . '/');
    }
    return $url;
}, 10, 3);
add_action('init', function () {

  $reserved = [
    'about',
    'blog',
    'contact',
    'privacy',
    'wp-admin',
    'wp-login.php',
  ];

  // add_rewrite_rule(  // creation_type 専用（music / movie / artwork のみ ※コンテンツ追加時は忘れないこと。 ）
  //   '^(?!' . implode('|', $reserved) . ')(music|movie|artwork)/?$',
  //   'index.php?creation_type=$matches[1]',
  //   'top'
  // );
});


/* --------------------------------
 * WordPress バージョン非表示
 * -------------------------------- */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/* CSS / JS の ?ver= を削除 */
function nowone_remove_ver_param($src) {
  if (strpos($src, '?ver=')) {
    $src = remove_query_arg('ver', $src);
  }
  return $src;
}
add_filter('style_loader_src', 'nowone_remove_ver_param', 9999);
add_filter('script_loader_src', 'nowone_remove_ver_param', 9999);


/* --------------------------------
 * XML-RPC 無効化
 * -------------------------------- */
add_filter('xmlrpc_enabled', '__return_false');

/* --------------------------------
 * 未ログイン管理画面アクセス禁止
 * -------------------------------- */
add_action('init', function () {
  if (is_admin() && !is_user_logged_in() && !defined('DOING_AJAX')) {
    wp_safe_redirect(home_url());
    exit;
  }
});

/* --------------------------------
 * 未ログイン wp-admin 直アクセス 404
 * -------------------------------- */
add_action('init', function () {
    if (
        !is_user_logged_in()
        && strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false
    ) {
        status_header(404);
        exit;
    }
});

/* --------------------------------
 * YouTube動画ID取得
 * -------------------------------- */
function nowone_get_youtube_id($url) {
  if (!$url) return '';

  // youtu.be/xxxx
  if (preg_match('~youtu\.be/([^\?&]+)~', $url, $m)) {
    return $m[1];
  }

  // youtube.com/watch?v=xxxx
  if (preg_match('~v=([^\?&]+)~', $url, $m)) {
    return $m[1];
  }

  return '';
}

/* --------------------------------
 * Sitemap 不要URL削除 inc/seo.phpに移動させること。
 * アクセスは/wp-sitemap.xmlにアクセスする。
 * -------------------------------- */
// author削除
add_filter( 'wp_sitemaps_add_provider', function( $provider, $name ) {
	if ( $name === 'users' ) {
		return false;
	}
	return $provider;
}, 10, 2 );
// portfolio 投稿削除
add_filter( 'wp_sitemaps_post_types', function( $post_types ) {
	unset( $post_types['portfolio'] );
	return $post_types;
});

/**
 * portfolio_genre を sitemap から除外
 */
add_filter('wp_sitemaps_taxonomies', function ($taxonomies) {
  unset($taxonomies['portfolio_genre']);
  return $taxonomies;
});


// add_filter('get_the_archive_description', function ($description) {
//   if (is_tax('creation_type')) {
//     $term = get_queried_object();
//     return 'NowOneの' . $term->name . '作品一覧。音楽・映像作品を掲載しています。';
//   }
//   return $description;
// });

add_action('wp_head', function () {
  if (is_tax()) {
    $term = get_queried_object();
    if ($term && !is_wp_error($term)) {
      $desc = 'NowOneの' . esc_html($term->name) . '作品一覧ページ。';
      echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
    }
  }
});

//////////
// Caution 緊急用
//////////
add_action('init', function () {
  flush_rewrite_rules();
});