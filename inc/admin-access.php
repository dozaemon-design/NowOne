<?php
/**
 * Admin access guard
 * - 購読者など低権限ユーザーを wp-admin に入れない
 * - ログイン後の遷移先も /portfolio/ に寄せる
 */

/**
 * ログイン後の遷移先（購読者は /portfolio/ へ）
 */
add_filter('login_redirect', function ($redirect_to, $requested_redirect_to, $user) {
  if (is_wp_error($user) || !($user instanceof WP_User)) {
    return $redirect_to;
  }

  // 購読者のみ強制
  if (in_array('subscriber', (array) $user->roles, true)) {
    return home_url('/portfolio/');
  }

  return $redirect_to;
}, 10, 3);

/**
 * wp-admin を購読者は常に弾く（admin-ajax/admin-post等は除外）
 */
add_action('admin_init', function () {
  if (!is_user_logged_in()) {
    return;
  }

  if (wp_doing_ajax()) {
    return;
  }

  $user = wp_get_current_user();
  if (!$user || empty($user->ID)) {
    return;
  }

  // admin-ajax.php / admin-post.php などは許可（フロント機能を壊しにくい）
  $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
  $request_path = (string) wp_parse_url($request_uri, PHP_URL_PATH);
  if ($request_path !== '') {
    if (strpos($request_path, '/wp-admin/admin-ajax.php') !== false) {
      return;
    }
    if (strpos($request_path, '/wp-admin/admin-post.php') !== false) {
      return;
    }
    if (strpos($request_path, '/wp-admin/async-upload.php') !== false) {
      return;
    }
  }

  if (in_array('subscriber', (array) $user->roles, true)) {
    wp_safe_redirect(home_url('/portfolio/'));
    exit;
  }
});

/**
 * 管理バーも非表示（購読者）
 */
add_filter('show_admin_bar', function ($show) {
  if (!is_user_logged_in()) {
    return $show;
  }

  $user = wp_get_current_user();
  if ($user && in_array('subscriber', (array) $user->roles, true)) {
    return false;
  }

  return $show;
});

