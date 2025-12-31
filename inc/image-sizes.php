<?php
/**
 * Image Sizes (Final)
 *
 * 方針：
 * - 元画像は1枚のみアップロード
 * - 用途ごとにサイズを生成
 * - 管理画面では軽量サムネイルを表示
 * - WordPressの自動スケール画像は無効化
 */

/* ========================================
 * WordPress 自動スケール画像を無効化
 * （1536 / 2048 系の謎画像対策）
 * ====================================== */
add_filter('big_image_size_threshold', '__return_false');


/* ========================================
 * アイキャッチ有効化
 * ====================================== */
add_theme_support('post-thumbnails');


/* ========================================
 * 画像サイズ定義
 * ====================================== */

/**
 * 一覧・カード用（正方形に近い）
 * archive / card / list
 */
add_image_size(
  'creation_thumb',
  768,
  768,
  true // トリミングしない（比率保持）
);

/**
 * シングルページ用メイン画像
 * hero / detail view
 */
add_image_size(
  'creation_detail',
  1980,
  1080,
  false
);

/**
 * ポップアップ・モーダル用
 * ポートフォリオ拡大表示など
 */
add_image_size(
  'popup_thumb',
  750,
  0,     // 高さ自由（比率保持）
  false
);


/* ========================================
 * 管理画面のサムネイルを小さく固定
 * ====================================== */
add_filter('admin_post_thumbnail_size', function () {
  return 'thumbnail';
});


/* ========================================
 * 生成する画像サイズを制御
 * ====================================== */
/**
 * ・不要な medium / large 等は作らない
 * ・必要なサイズのみ生成
 * ・投稿タイプに依存しない（安定重視）
 */
add_filter(
  'intermediate_image_sizes_advanced',
  function ($sizes) {

    // 必要なサイズだけ残す
    return array_intersect_key(
      $sizes,
      [
        'creation_thumb'  => true,
        'creation_detail' => true,
        'popup_thumb'     => true,
      ]
    );
  },
  10,
  1
);