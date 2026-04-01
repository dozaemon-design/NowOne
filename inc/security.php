<?php
/**
 * セキュリティと管理画面アクセス制御
 *
 * このファイルは、XML-RPC の停止と
 * 未ログイン時の wp-admin アクセス制御を担当する。
 */

// XML-RPC を無効化する。
add_filter('xmlrpc_enabled', '__return_false');

add_action('init', function () {
  // 未ログインで wp-admin 直アクセスした場合はログイン画面へ送る。
  if (
    !is_user_logged_in()
    && strpos($_SERVER['REQUEST_URI'], '/wp-admin') !== false
    && strpos($_SERVER['REQUEST_URI'], 'wp-login.php') === false
    && strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') === false
  ) {
    wp_redirect(home_url('/wp-login.php'));
    exit;
  }
});

add_action('admin_init', function () {
  // admin-ajax.php を除き、未ログインの管理画面アクセスは認証へ流す。
  if (
    !is_user_logged_in()
    && !wp_doing_ajax()
  ) {
    auth_redirect();
  }
});
?>
