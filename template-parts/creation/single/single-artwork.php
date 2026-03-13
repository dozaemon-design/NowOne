<?php
/**
 * Single Music Template
 */

$title = get_the_title();

// ACF（生データ）
$artwork = get_field('artwork_fields'); // ACFグループ化フィールド取得
$img = get_creation_image('detail', 'creation_detail');
// メイン画像 inc/helpers-image.phpより取得
$artist  = $artwork['artwork_artist'] ?? ''; // 作画者 artworkのみ使用
$size  = $artwork['artwork_size'] ?? ''; // 画像サイズ artworkのみ使用

$druft_line_ttl = $artwork['artwork_druft_line_title'] ?? ''; // 下書きタイトル artworkのみ使用
$druft_line_img = $artwork['artwork_druft_line_img'] ?? ''; // 下書き画像 artworkのみ使用
$druft_paint_ttl = $artwork['artwork_druft_paint_title'] ?? ''; // 下書きタイトル artworkのみ使用
$druft_paint_img = $artwork['artwork_druft_paint_img'] ?? ''; // 下書き画像 artworkのみ使用
$desc = get_field('creation_description'); // 共通フィールドの説明文の取得
$material = $artwork['artwork_material'] ?? ''; // 作画用の機材にするか法にするか未定。
$url = $artwork['artwork_artist_url'] ?? ''; // もし外部者が描いてくれたら…と期待を込めて。

$back_url = get_post_type_archive_link('creation') ?: home_url('/');
$artwork_term = get_term_by('slug', 'artwork', 'creation_type');
if ($artwork_term && !is_wp_error($artwork_term)) {
	$artwork_term_link = get_term_link($artwork_term);
	if (!is_wp_error($artwork_term_link)) {
		$back_url = $artwork_term_link;
	}
}
?>

<article class="c-single-creation p-creation-single">
	<?php if ($img): ?>
		<figure class="p-creation-single__img">
			<?= render_creation_picture($img, 'single', true); ?>
		</figure>
	<?php endif; ?>

	<section class="l-content l-content--inline">
			<h1>
				<?php echo esc_html($title); ?>
			</h1>
			<?php if ($artist || $size || $material):  //作画者,size,使用機材 ?>
				<ul class="l-cluster c-single-creation__labels ">
					<?php if ($artist): ?>
						<li class="c-single-creation__label">Artist：<?php echo esc_html($artist); ?></li>
					<?php endif; ?>
					<?php if ($size): ?>
						<li class="c-single-creation__label">Size：<?php echo esc_html($size); ?></li>
						<?php endif; ?>
					<?php if ($material): ?><li class="c-single-creation__label">Material：<?php echo esc_html($material); ?></li>
						<?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php if ($desc): //共通の説明文があれば ?>
				<div class="c-text c-single-creation__text">
					<?php echo wp_kses_post($desc); ?>
				</div>
			<?php endif; ?>
            <?php if ($druft_line_ttl || $druft_line_img): //下書きタイトル、下書き画像 ?>
                <?php if ($druft_line_ttl): ?>
                    <h2 class="c-media-badge"><?php echo esc_html($druft_line_ttl); ?></h2>
                <?php endif; ?>

                <?php if ($druft_line_img): ?>
                    <figure class="p-creation-single--draft__img">
                    <?= render_creation_picture($druft_line_img, 'single', true); ?>
                    </figure>
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($druft_paint_ttl || $druft_paint_img): //下書きタイトル、下書き画像 ?>
                <?php if ($druft_paint_ttl): ?>
                    <h2 class="c-media-badge"><?php echo esc_html($druft_paint_ttl); ?></h2>
                <?php endif; ?>

                <?php if ($druft_paint_img): ?>
                    <figure class="p-creation-single--draft__img">
                    <?= render_creation_picture($druft_paint_img, 'single', true); ?>
                    </figure>
                <?php endif; ?>
            <?php endif; ?>
			<?php
			get_template_part('template-parts/component/back-link', '', [
				'href' => $back_url,
				'label' => '一覧に戻る',
			]);
			?>
    </section>
</article>

		<?php
// echo '<pre>';
// var_dump($artwork);
// echo '</pre>';
		?>
