<?php
/**
 * head 出力整理とフロント最適化
 *
 * このファイルは、favicon 出力、WordPress 標準 head 要素の整理、
 * 公開フロント向けの不要スタイル抑制を担当する。
 */

// favicon / manifest を head に出力する。
add_action('wp_head', function () {
  $dir = get_template_directory_uri() . '/assets/img/common/favicon';

  echo <<<HTML
<link rel="icon" href="{$dir}/favicon.svg" type="image/svg+xml">
<link rel="icon" href="{$dir}/favicon.ico" sizes="any">
<link rel="apple-touch-icon" href="{$dir}/apple-touch-icon.png">
<link rel="manifest" href="{$dir}/site.webmanifest">
HTML;
});

// WordPress バージョン情報を非表示にする。
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/**
 * 公開フロントだけで WordPress 標準の inline style を整理する。
 * 管理画面とログイン中の確認環境は壊さないため除外する。
 *
 * @return bool
 */
function nowone_should_trim_wp_frontend_assets() {
  return !is_admin() && !is_user_logged_in() && !is_customize_preview();
}

add_action('init', function () {
  // 管理画面やログイン中は WordPress 標準挙動を維持する。
  if (!nowone_should_trim_wp_frontend_assets()) {
    return;
  }

  // emoji 関連のスクリプトとスタイルを停止する。
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

  // block / theme.json 系の標準スタイル出力を停止する。
  remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
  remove_action('wp_footer', 'wp_enqueue_global_styles', 1);
  remove_action('wp_enqueue_scripts', 'wp_enqueue_stored_styles');
  remove_action('wp_footer', 'wp_enqueue_stored_styles', 1);

  // sizes="auto" の自動付与と、その補正 CSS も止める。
  add_filter('wp_img_tag_add_auto_sizes', '__return_false');
  remove_action('wp_head', 'wp_enqueue_img_auto_sizes_contain_css_fix', 0);
  remove_action('wp_head', 'wp_print_auto_sizes_contain_css_fix', 1);
}, 20);

add_action('wp_enqueue_scripts', function () {
  // 対象外の環境では dequeue しない。
  if (!nowone_should_trim_wp_frontend_assets()) {
    return;
  }

  // 実際に enqueue 済みの WordPress 標準スタイルを除去する。
  wp_dequeue_style('wp-emoji-styles');
  wp_deregister_style('wp-emoji-styles');
  wp_dequeue_style('wp-block-library');
  wp_dequeue_style('wp-block-library-theme');
  wp_dequeue_style('classic-theme-styles');
  wp_dequeue_style('global-styles');
  wp_dequeue_style('global-styles-css-custom-properties');
}, 100);

/**
 * CSS / JS の ?ver= をハッシュ化してキャッシュバスティングを維持する。
 *
 * @param string $src
 * @return string
 */
function nowone_hash_ver_param($src) {
  // ver パラメータ付きアセットだけをハッシュ値へ変換する。
  if (strpos($src, '?ver=')) {
    preg_match('/?ver=([^&"]+)/', $src, $matches);
    if (!empty($matches[1])) {
      $hashed_ver = md5($matches[1]);
      $hashed_ver = substr($hashed_ver, 0, 8);

      $src = remove_query_arg('ver', $src);
      $src = add_query_arg('v', $hashed_ver, $src);
    }
  }

  return $src;
}

add_filter('style_loader_src', 'nowone_hash_ver_param', 9999);
add_filter('script_loader_src', 'nowone_hash_ver_param', 9999);

// HTML 出力の最終段で残存する ?ver= もハッシュ化する。
add_action('template_redirect', function () {
  ob_start(function ($html) {
    return preg_replace_callback(
      '/\?ver=([^&"\'\s]+)/',
      function ($matches) {
        $hashed = substr(md5($matches[1]), 0, 8);
        return '?v=' . $hashed;
      },
      $html
    );
  });
}, 9);

// 出力バッファがあれば最後に flush する。
add_action('shutdown', function () {
  if (ob_get_level() > 0) {
    ob_end_flush();
  }
});
?>
