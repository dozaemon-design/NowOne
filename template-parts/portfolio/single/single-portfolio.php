<?php
/**
 * Single Portfolio template part
 *
 * ACF field group: Portforio Fields
 * - portfolio_description
 * - portfolio_start_date
 * - portfolio_end_date
 * - portfolio_link
 * - portfolio_main_img
 * - portfolio_thumb_img
 * - portfolio_sub_page_img
 * - portfolio_sub_page2_img
 * - portfolio_sub_page3_img
 * - portfolio_sub_page4_img
 * - portfolio_sub_page5_img
 * - portfolio_main_img_title
 * - portfolio_sub_page_img_title
 * - portfolio_sub_page2_img_title
 * - portfolio_sub_page3_img_title
 * - portfolio_sub_page4_img_title
 * - portfolio_sub_page5_img_title
 */

$post_id = get_the_ID();

$portfolio_description = function_exists('get_field') ? get_field('portfolio_description', $post_id) : '';
$portfolio_start_date = function_exists('get_field') ? get_field('portfolio_start_date', $post_id) : '';
$portfolio_end_date = function_exists('get_field') ? get_field('portfolio_end_date', $post_id) : '';
$portfolio_link = function_exists('get_field') ? get_field('portfolio_link', $post_id) : '';

$portfolio_main_img = function_exists('get_field') ? get_field('portfolio_main_img', $post_id) : null;
$portfolio_sub_page_img = function_exists('get_field') ? get_field('portfolio_sub_page_img', $post_id) : null;
$portfolio_sub_page2_img = function_exists('get_field') ? get_field('portfolio_sub_page2_img', $post_id) : null;
$portfolio_sub_page3_img = function_exists('get_field') ? get_field('portfolio_sub_page3_img', $post_id) : null;
$portfolio_sub_page4_img = function_exists('get_field') ? get_field('portfolio_sub_page4_img', $post_id) : null;
$portfolio_sub_page5_img = function_exists('get_field') ? get_field('portfolio_sub_page5_img', $post_id) : null;

$portfolio_main_img_title = function_exists('get_field') ? (string) get_field('portfolio_main_img_title', $post_id) : '';
$portfolio_sub_page_img_title = function_exists('get_field') ? (string) get_field('portfolio_sub_page_img_title', $post_id) : '';
$portfolio_sub_page2_img_title = function_exists('get_field') ? (string) get_field('portfolio_sub_page2_img_title', $post_id) : '';
$portfolio_sub_page3_img_title = function_exists('get_field') ? (string) get_field('portfolio_sub_page3_img_title', $post_id) : '';
$portfolio_sub_page4_img_title = function_exists('get_field') ? (string) get_field('portfolio_sub_page4_img_title', $post_id) : '';
$portfolio_sub_page5_img_title = function_exists('get_field') ? (string) get_field('portfolio_sub_page5_img_title', $post_id) : '';

$resolve_media_title = function (string $title, $img) {
  $title = trim($title);
  if ($title !== '') {
    return $title;
  }
  if (is_array($img) && !empty($img['title'])) {
    return (string) $img['title'];
  }
  if (is_array($img) && !empty($img['ID'])) {
    return (string) get_the_title((int) $img['ID']);
  }
  return '';
};

$portfolio_media_items = [
  ['img' => $portfolio_main_img, 'title' => $resolve_media_title($portfolio_main_img_title, $portfolio_main_img)],
  ['img' => $portfolio_sub_page_img, 'title' => $resolve_media_title($portfolio_sub_page_img_title, $portfolio_sub_page_img)],
  ['img' => $portfolio_sub_page2_img, 'title' => $resolve_media_title($portfolio_sub_page2_img_title, $portfolio_sub_page2_img)],
  ['img' => $portfolio_sub_page3_img, 'title' => $resolve_media_title($portfolio_sub_page3_img_title, $portfolio_sub_page3_img)],
  ['img' => $portfolio_sub_page4_img, 'title' => $resolve_media_title($portfolio_sub_page4_img_title, $portfolio_sub_page4_img)],
  ['img' => $portfolio_sub_page5_img, 'title' => $resolve_media_title($portfolio_sub_page5_img_title, $portfolio_sub_page5_img)],
];
?>

<article class="p-portfolio--content">
    <div class="p-portfolio--content__layout">
	      <div class="p-portfolio--content__body">
	        <h1 class="p-portfolio--content__title"><?php the_title(); ?></h1>
	        <?php
	        $tool_terms = get_the_terms($post_id, 'portfolio_tool');
	        $role_terms = get_the_terms($post_id, 'portfolio_role');
	        ?>
	        <ul class="p-portfolio--content__list">
            <?php if (!empty($role_terms) && !is_wp_error($role_terms)) : /*役割*/ ?>
            <?php if (!empty($tool_terms) && !is_wp_error($tool_terms)) : /*ツール*/ ?>
	            <li class="p-portfolio--content__term">
	              <ul class="l-cluster p-portfolio-list__roles">
	                <?php foreach ($role_terms as $term) : ?>
	                  <li class="p-portfolio-list__role">
	                    <?php echo esc_html($term->name); ?>
	                  </li>
	                <?php endforeach; ?>
	              </ul>
	            </li>
	          <?php endif; ?>
              <li class="p-portfolio--content__term">
	              <ul class="l-cluster p-portfolio-list__tools">
	                <?php foreach ($tool_terms as $term) : ?>
	                  <li class="p-portfolio-list__tool">
	                    <?php echo esc_html($term->name); ?>
	                  </li>
	                <?php endforeach; ?>
	              </ul>
	            </li>
	          <?php endif; ?>
	          <?php if ($portfolio_description): ?>
	            <li class="p-portfolio--content__description">
	                  <?php echo wp_kses_post($portfolio_description); ?>
	            </li>
	          <?php endif; ?>
	          <?php if ($portfolio_start_date || $portfolio_end_date) : ?>
	            <li class="p-portfolio--content__dates">
	              <time class="p-portfolio--content__date">
	                制作期間：<?php echo esc_html($portfolio_start_date); ?><?php echo ($portfolio_start_date && $portfolio_end_date) ? ' ~ ' : ''; ?><?php echo esc_html($portfolio_end_date); ?>
	              </time>
	            </li>
		          <?php endif; ?>
		          <?php if ($portfolio_link): ?>
		            <li>
		              <a href="<?php echo esc_url($portfolio_link); ?>" class="p-portfolio--content__link" target="_blank" rel="noopener">
										<img class="p-portfolio--content__icon" src="<?php echo get_template_directory_uri(); ?>/assets/img/portfolio/common/icon_link.svg" alt="icon link" width="100%" height="auto">Project Link
									</a>
		            </li>
		          <?php endif; ?>
	        </ul>
        </div>
        <?php if (has_post_thumbnail()) : ?>
          <div class="p-portfolio--content__media">
            <figure class="p-portfolio--content__thumb">
              <?php the_post_thumbnail('portfolio_thumb'); ?>
            </figure>
          </div>
	      <?php endif; ?>
	    </div>
	    <ul class="l-cluster p-portfolio--content__media-list">
	      <?php foreach ($portfolio_media_items as $item) : ?>
		        <?php
		        $img = $item['img'] ?? null;
		        $title = (string) ($item['title'] ?? '');
		        $img_html = ($img && function_exists('render_portfolio_acf_image'))
		          ? render_portfolio_acf_image($img, 'portfolio_thumb', ['data-hover-zoom' => 'img'])
		          : '';
		        if (!$img_html) {
		          continue;
		        }

	        $attachment_id = (is_array($img) && !empty($img['ID'])) ? (int) $img['ID'] : 0;
	        $popup_src = $attachment_id ? wp_get_attachment_image_url($attachment_id, 'popup_thumb') : '';
	        if (!$popup_src && $attachment_id) {
	          $popup_src = wp_get_attachment_url($attachment_id);
	        }
	        if (!$popup_src) {
	          continue;
	        }

	        $popup_alt = '';
	        if (is_array($img) && !empty($img['alt'])) {
	          $popup_alt = (string) $img['alt'];
	        } elseif ($attachment_id) {
	          $popup_alt = (string) get_post_meta($attachment_id, '_wp_attachment_image_alt', true);
	        }
	        ?>
	        <li class="p-portfolio--content__media-item c-hover-invert c-hover-zoom c-reveal js-reveal">
	          <a
	            class="p-portfolio--content__media-link"
	            href="<?php echo esc_url($popup_src); ?>"
	            data-portfolio-popup="1"
	            data-popup-src="<?php echo esc_url($popup_src); ?>"
	            data-popup-alt="<?php echo esc_attr($popup_alt); ?>"
	            data-popup-title="<?php echo esc_attr($title); ?>"
	          >
	            <figure class="p-portfolio--content__media-thumb">
	              <?php echo $img_html; ?>
	            </figure>
	            <?php if ($title !== '') : ?>
	              <h2 class="p-portfolio--content__media-title">
	                <?php echo esc_html($title); ?>
	              </h2>
	            <?php endif; ?>
	          </a>
	        </li>
	      <?php endforeach; ?>
		      </ul>
        <?php
        $portfolio_archive_url = get_post_type_archive_link('portfolio');
        if (!$portfolio_archive_url) {
          $portfolio_archive_url = home_url('/portfolio/');
        }

        $back_url = $portfolio_archive_url;
        $referer = wp_get_referer();
        if ($referer) {
          $home_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
          $ref_host = wp_parse_url($referer, PHP_URL_HOST);
          $ref_path = (string) wp_parse_url($referer, PHP_URL_PATH);

          $is_internal = ($home_host && $ref_host && strtolower($home_host) === strtolower($ref_host));
          $is_listing = (
            strpos($ref_path, '/portfolio') === 0 ||
            strpos($ref_path, '/portfolio_genre') === 0 ||
            strpos($ref_path, '/portfolio_role') === 0 ||
            strpos($ref_path, '/portfolio_tool') === 0
          );
          $is_current = untrailingslashit($referer) === untrailingslashit(get_permalink($post_id));

          if ($is_internal && $is_listing && !$is_current) {
            $back_url = $referer;
          }
        }
        ?>
        <ul class="p-portfolio-back">
          <li class="p-portfolio-back__item">
            <a class="p-portfolio-back__link" href="<?php echo esc_url($back_url); ?>">一覧に戻る</a>
          </li>
        </ul>
	</article>
