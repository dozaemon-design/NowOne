<?php
/**
 * CSS / JS enqueue
 */

function nowone_enqueue_assets() {

	/* =========================
	 * CSS
	 * ========================= */
	// Portfolio関連のページ（シングル、アーカイブ、タクソノミー）ではポートフォリオ専用のスタイルを読み込み、それ以外のページでは共通のスタイルを読み込む
		if (is_singular('portfolio') || is_post_type_archive('portfolio') || is_tax('portfolio_genre')) {
			wp_enqueue_style(
				'nowone-app-portfolio',
				get_template_directory_uri() . '/assets/css/app-portfolio.css',
				array(),
				NOWONE_THEME_VERSION,
				'all'
			);

			if (is_singular('portfolio')) {
				wp_enqueue_script(
					'nowone-portfolio-media-popup',
				get_template_directory_uri() . '/assets/js/portfolio/media-popup.js',
				[],
				NOWONE_THEME_VERSION,
				true
			);
		}

		wp_enqueue_script(
			'nowone-portfolio-nav-chips',
			get_template_directory_uri() . '/assets/js/portfolio/portfolio-nav-chips.js',
			[],
			NOWONE_THEME_VERSION,
			true
		);
	} else {
		wp_enqueue_style(
			'nowone-app',
			get_template_directory_uri() . '/assets/css/app.css',
			array(),
			NOWONE_THEME_VERSION,
			'all'
		);

		// Page transition（portfolio配下 / contact は除外）
		if (!is_page(['contact', 'contact-thanks'])) {
			wp_enqueue_script(
				'nowone-page-transition',
				get_template_directory_uri() . '/assets/js/common/page-transition.js',
				[],
				NOWONE_THEME_VERSION,
				true
			);
		}
	}

	/* =========================
	 * jQuery
	 * ========================= */
	// WordPress同梱のjQueryを使用
	wp_enqueue_script('jquery');

	/* =========================
	 * JS
	 * ========================= */
	wp_enqueue_script( // jQuery Easing JS
		'nowone-easing',
		get_template_directory_uri() . '/assets/js/lib/jquery.easing.1.3.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	// Home text（Splitting.js使用）
	if (is_front_page() || is_page('contact-thanks')) {
		// Splitting.js
		wp_enqueue_script(
			'splitting',
			get_template_directory_uri() . '/assets/js/vendor/splitting.min.js',
			[],
			NOWONE_THEME_VERSION,
			true
		);
		// Splitting用CSS
		wp_enqueue_style(
			'splitting',
			get_template_directory_uri() . '/assets/css/vendor/splitting.css',
			[],
			NOWONE_THEME_VERSION
		);
		// Home text用JS
		wp_enqueue_script(
			'home-text',
			get_template_directory_uri() . '/assets/js/creation/production/home-text.js',
			['splitting'],
			NOWONE_THEME_VERSION,
			true
		);
	}
	wp_enqueue_script( // Base JS
		'nowone-base',
		get_template_directory_uri() . '/assets/js/base.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // Global Navigation JS
		'nowone-global-nav',
		get_template_directory_uri() . '/assets/js/creation/component/global-nav.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // YouTube embed JS
		'nowone-youtube',
		get_template_directory_uri() . '/assets/js/creation/component/youtube.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	if (is_front_page()) { //トップページのみ読み込み
		wp_enqueue_script(
			'nowone-home',
			get_template_directory_uri() . '/assets/js/creation/production/home.js',
			['jquery'],
			NOWONE_THEME_VERSION,
			true
		);
	}
	wp_enqueue_script( // list animation JS
		'nowone-reveal',
		get_template_directory_uri() . '/assets/js/creation/component/reveal.js',
		[],
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // list animation JS
		'nowone-header',
		get_template_directory_uri() . '/assets/js/creation/component/header.js',
		[],
		NOWONE_THEME_VERSION,
		true
	);
	if (is_page('contact')) { // お問い合わせのみ読み込み
		wp_enqueue_script(
			'contact-form',
			get_theme_file_uri('/assets/js/creation/component/contact.js'),
			[],
			NOWONE_THEME_VERSION,
			true
		);
	};
} //end
add_action('wp_enqueue_scripts', 'nowone_enqueue_assets');

/* =========================
 * Contact Form 7 assets
 * =========================
 * - 基本はフォームページのみ読み込む（それ以外は停止）
 * - 固定ページ以外（アーカイブ等）やウィジェット等でショートコードを使う場合は条件を追加する
 */
function nowone_should_load_cf7_assets(): bool {
	if (is_admin()) {
		return true;
	}

	// お問い合わせページ
	if (is_page('contact')) {
		return true;
	}

	// 念のため：本文内にショートコードがある場合
	$post = get_post();
	if ($post && isset($post->post_content) && has_shortcode($post->post_content, 'contact-form-7')) {
		return true;
	}

	return false;
}

add_filter('wpcf7_load_js', function ($load) {
	return nowone_should_load_cf7_assets();
});

add_filter('wpcf7_load_css', function ($load) {
	return nowone_should_load_cf7_assets();
});


	/* =========================
	 * Admin CSS
	 * ========================= */
	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_style(
			'nowone-admin',
			get_template_directory_uri() . '/assets/css/admin.css',
			[],
			NOWONE_THEME_VERSION
		);
	});
	/* =========================
	 * Admin JS
	 * ========================= */
	function nowone_admin_scripts( $hook ) {

    if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) return;

    wp_enqueue_script(
        'nowone-admin',
        get_template_directory_uri() . '/assets/js/admin.js',
        ['jquery'],
        NOWONE_THEME_VERSION,
        true
				);
		}
		add_action( 'admin_enqueue_scripts', 'nowone_admin_scripts' );

	// Creation Type タクソノミーのチェックボックスを上部に固定
		add_filter('wp_terms_checklist_args', function ($args, $post_id) {
    if ($args['taxonomy'] === 'creation_type') {
        $args['checked_ontop'] = false;
    }
    return $args;
}, 10, 2);
