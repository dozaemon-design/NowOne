<?php
/**
 * Portfolio access guard
 * - /portfolio/ 配下は未ログイン時に wp-login.php へ誘導
 * - ログイン後は /portfolio/ へ戻す（固定）
 */

add_action('template_redirect', function () {
  if (is_user_logged_in()) {
    return;
  }

  if (is_admin() || wp_doing_ajax() || (defined('REST_REQUEST') && REST_REQUEST)) {
    return;
  }

  $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
  $request_path = wp_parse_url($request_uri, PHP_URL_PATH);
  if (!$request_path) {
    return;
  }

  // サブディレクトリ配下でも破綻しないよう、home_url() からベースパスを抽出
  $portfolio_base_path = wp_parse_url(home_url('/portfolio/'), PHP_URL_PATH);
  if (!$portfolio_base_path) {
    return;
  }

  $request_norm = trailingslashit($request_path);
  $base_norm = trailingslashit($portfolio_base_path);

  if (strpos($request_norm, $base_norm) !== 0) {
    return;
  }

  // login画面自体は除外（ループ防止）
  if (strpos($request_norm, '/wp-login.php/') !== false) {
    return;
  }

  $redirect_to = home_url('/portfolio/');
  wp_safe_redirect(wp_login_url($redirect_to));
  exit;
}, 1);

