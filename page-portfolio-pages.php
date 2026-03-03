<?php
/**
 * Template Name: Portfolio Pages
 * Template Post Type: page
 *
 * /portfolio/ (CPT archive) と競合を避けるため、固定ページは /portfolio-pages/ 配下で運用する。
 * 例: /portfolio-pages/profile/
 *
 * Portfolio配下（= portfolio-pages配下）の固定ページ用「中継」テンプレート。
 * slug に応じて template-parts/portfolio/page-{slug}.php を読み込む。
 */

$page_id = get_queried_object_id();
$slug = $page_id ? get_post_field('post_name', $page_id) : '';

if ($slug) {
	get_template_part('template-parts/portfolio/page', $slug);
} else {
	get_template_part('template-parts/portfolio/page');
}
