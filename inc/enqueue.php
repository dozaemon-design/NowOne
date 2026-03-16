<?php
/**
 * CSS / JS enqueue
 */

function nowone_enqueue_assets() {
	$is_portfolio_context = (is_singular('portfolio') || is_post_type_archive('portfolio') || is_tax('portfolio_genre'));
	$has_app_bundle = file_exists(get_theme_file_path('/assets/build/app.js'));
	$has_portfolio_bundle = file_exists(get_theme_file_path('/assets/build/app-portfolio.js'));
	$use_bundle = $is_portfolio_context ? $has_portfolio_bundle : $has_app_bundle;

	/* =========================
	 * CSS
	 * ========================= */
		// Portfolio関連のページ（シングル、アーカイブ、タクソノミー）ではポートフォリオ専用のスタイルを読み込み、それ以外のページでは共通のスタイルを読み込む
			if ($is_portfolio_context) {
				wp_enqueue_style(
					'nowone-app-portfolio',
					get_template_directory_uri() . '/assets/css/app-portfolio.css',
					array(),
					NOWONE_THEME_VERSION,
					'all'
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
			$page_transition_bundle = '/assets/build/page-transition.js';
			if (file_exists(get_theme_file_path($page_transition_bundle))) {
				wp_enqueue_script(
					'nowone-page-transition',
					get_template_directory_uri() . $page_transition_bundle,
					[],
					NOWONE_THEME_VERSION,
					true
				);
				wp_script_add_data('nowone-page-transition', 'type', 'module');
			}
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
	if ($use_bundle) {
		$bundle_file = $is_portfolio_context ? '/assets/build/app-portfolio.js' : '/assets/build/app.js';
		$bundle_handle = $is_portfolio_context ? 'nowone-bundle-portfolio' : 'nowone-bundle';
		wp_enqueue_script(
			$bundle_handle,
			get_template_directory_uri() . $bundle_file,
			['jquery'],
			NOWONE_THEME_VERSION,
			true
		);
		// Viteの出力はESM（importを含む）なので type="module" で読み込む
		// - WPのバージョン/環境差で script attributes が効かないケースがあるため、後段の script_loader_tag でも保険を入れる
		wp_script_add_data($bundle_handle, 'type', 'module');
	}

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
		$home_text_bundle = '/assets/build/home-text.js';
		if (file_exists(get_theme_file_path($home_text_bundle))) {
			wp_enqueue_script(
				'home-text',
				get_template_directory_uri() . $home_text_bundle,
				['splitting'],
				NOWONE_THEME_VERSION,
				true
			);
			wp_script_add_data('home-text', 'type', 'module');
		}
	}

	if (is_front_page()) { //トップページのみ読み込み
		$home_bundle = '/assets/build/home.js';
		if (file_exists(get_theme_file_path($home_bundle))) {
			wp_enqueue_script(
				'nowone-home',
				get_template_directory_uri() . $home_bundle,
				['jquery'],
				NOWONE_THEME_VERSION,
				true
			);
			wp_script_add_data('nowone-home', 'type', 'module');
		}
	}
	if (is_page('contact')) { // お問い合わせのみ読み込み
		$contact_bundle = '/assets/build/contact.js';
		if (file_exists(get_theme_file_path($contact_bundle))) {
			wp_enqueue_script(
				'nowone-contact',
				get_template_directory_uri() . $contact_bundle,
				[],
				NOWONE_THEME_VERSION,
				true
			);
			wp_script_add_data('nowone-contact', 'type', 'module');
		}
	};
} //end
add_action('wp_enqueue_scripts', 'nowone_enqueue_assets');

/**
 * Ensure Vite bundles are loaded as ESM.
 * - Fixes "Cannot use import statement outside a module"
 */
	add_filter('script_loader_tag', function ($tag, $handle, $src) {
		$module_handles = [
			'nowone-bundle',
			'nowone-bundle-portfolio',
			'nowone-page-transition',
			'home-text',
			'nowone-home',
			'nowone-contact',
			'nowone-admin',
		];
		if (!in_array($handle, $module_handles, true)) {
			return $tag;
		}
	// 既に type="module" の場合はそのまま
	if (preg_match('/\btype=(["\'])module\1/i', $tag)) {
		return $tag;
	}
	// type が付いている場合は module に差し替える（type="text/javascript" 等を潰す）
	if (preg_match('/\btype=(["\']).*?\1/i', $tag)) {
		return preg_replace('/\btype=(["\']).*?\1/i', 'type="module"', $tag, 1);
	}
	// type が無い場合は付与
	return preg_replace('/^<script\b/i', '<script type="module"', $tag, 1);
}, 10, 3);

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
	
		$admin_bundle = '/assets/build/admin.js';
		if (!file_exists(get_theme_file_path($admin_bundle))) return;

	    wp_enqueue_script(
	        'nowone-admin',
	        get_template_directory_uri() . $admin_bundle,
	        ['jquery'],
	        NOWONE_THEME_VERSION,
	        true
					);
		wp_script_add_data('nowone-admin', 'type', 'module');
			}
			add_action( 'admin_enqueue_scripts', 'nowone_admin_scripts' );

	// Creation Type タクソノミーのチェックボックスを上部に固定
		add_filter('wp_terms_checklist_args', function ($args, $post_id) {
    if ($args['taxonomy'] === 'creation_type') {
        $args['checked_ontop'] = false;
    }
    return $args;
}, 10, 2);
