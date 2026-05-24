<?php
/**
 * 共通ヘルパー関数
 *
 * このファイルは、複数テンプレートや機能から利用する
 * 汎用関数をまとめて管理する。
 */

/**
 * meta description を生成する。
 *
 * @return string
 */
function nowone_meta_description() {
  // フロントページ用の固定文言。
  if (is_front_page()) {
    return 'NowOneの公式サイト。音楽・映像・デザイン制作。';
  }

  // creation single は ACF 優先で説明文を返す。
  if (is_singular('creation')) {
    $post_id = get_queried_object_id();
    if (!$post_id) {
      return '';
    }

    // ACF で個別指定されていれば最優先で採用する。
    if (function_exists('get_field')) {
      $acf_meta = get_field('creation_meta_description', $post_id);
      if (!empty($acf_meta)) {
        return wp_strip_all_tags($acf_meta);
      }
    }

    // 未指定時はタイトルと taxonomy 名を組み合わせる。
    $title = get_the_title($post_id);
    $terms = wp_get_post_terms($post_id, 'creation_type');
    $suffix = (!empty($terms) && !is_wp_error($terms)) ? $terms[0]->name : '';

    return trim($title . '｜' . $suffix . '｜NowOne');
  }

  // taxonomy / category / tag は説明文または名称ベースで返す。
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
 * 現在のグローバルナビ判定
 *
 * @param string $type home | music | movie など
 * @return bool
 */
function nowone_is_current_nav($type) {
  // home はフロントページと投稿一覧の両方を現在地扱いにする。
  if ($type === 'home') {
    return is_front_page() || is_home();
  }

  // URL の第1セグメントでナビ現在地を判定する。
  $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  $segments = explode('/', $request);

  return isset($segments[0]) && $segments[0] === $type;
}

// 現在 URL の先頭セグメントを返す。
function nowone_get_current_segment() {
  $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  return explode('/', $path)[0] ?? '';
}

// Portfolio の profile ページ ID を使い回すため静的キャッシュする。
function nowone_get_portfolio_profile_post_id() {
  static $cached = null;

  if ($cached !== null) {
    return $cached;
  }

  $posts  = get_posts([
    'name'        => 'profile',
    'post_type'   => 'portfolio',
    'post_status' => 'publish',
    'numberposts' => 1,
  ]);
  $cached = !empty($posts) ? (int) $posts[0]->ID : 0;
  return $cached;
}

/**
 * YouTube動画ID取得
 *
 * @param string $url
 * @return string
 */
function nowone_get_youtube_id($url) {
  // URL 未設定時は空で返す。
  if (!$url) {
    return '';
  }

  // 短縮 URL 形式に対応。
  if (preg_match('~youtu\.be/([^\?&]+)~', $url, $m)) {
    return $m[1];
  }

  // 通常の watch URL 形式に対応。
  if (preg_match('~v=([^\?&]+)~', $url, $m)) {
    return $m[1];
  }

  return '';
}
?>
