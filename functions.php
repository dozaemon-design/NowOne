<?php
/**
 * Theme bootstrap
 */
$inc_files = [
    'acf.php',
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
 * Generate meta description
 */
function nowone_meta_description() {
  if (is_front_page()) {
    return 'NowOneのDTM制作、Web制作公式サイト';
  }
  if (is_singular()) {
    return get_the_excerpt();
  }
  return get_bloginfo('description');
}

/**
 * Enable site icon support
 */
add_theme_support('site-icon');

/**
 * 現在のグローバルナビ判定
 *
 * @param string $type home | music | movie など
 * @return bool
 */
function nowone_is_current_nav($type) {

  // HOME
  if ($type === 'home') {
    return is_front_page();
  }

  // creation_type 判定
  if (is_post_type_archive('creation') || is_singular('creation')) {
    return get_query_var('creation_type') === $type;
  }

  return false;
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
/**
 * Creation アーカイブページのリダイレクト
 */

add_action('init', function () {
    add_rewrite_rule(
        '^([^/]+)/?$',
        'index.php?creation_type=$matches[1]',
        'top'
    );
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