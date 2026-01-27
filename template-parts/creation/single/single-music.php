<?php
/**
 * Single Music Template
 */

$title = get_the_title();

// ACF（生データ）
$music = get_field('music_fields'); // ACFグループ化フィールド取得
$img = get_creation_image('detail', 'creation_detail');
// メイン画像 inc/helpers-image.phpより取得
$composer  = $music['music_composer'] ?? ''; // 作曲者 musicのみ使用
$bpm  = $music['music_bpm'] ?? ''; // 再生時間 musicのみ使用
$music_key  = $music['music_key'] ?? ''; // 再生時間 musicのみ使用 keyはphp内部で関数があるので使わない事。
$time = $music['music_length'] ?? ''; // 再生時間 musicのみ使用
$desc = get_field('creation_description'); // 共通フィールドの説明文の取得
$video_id = nowone_get_youtube_id( //youtubeの動画IDを取得
	$music['music_youtube_link'] ?? ''
);
$tc_url = $music['music_tunecore_link'] ?? ''; // TuneCoreリンク取得
$spotify_url = $music['music_spotify_link'] ?? ''; // Spotifyリンク取得
$apple_url = $music['music_applemusic_link'] ?? ''; // Apple Musicリンク取得

$has_streaming =
	$tc_url || $spotify_url || $apple_url;
?>

<article class="c-single-creation p-creation-single">
	<?php if ($img): ?>
		<figure class="p-creation-single__img">
			<?= render_creation_picture($img, 'single', true); ?>
		</figure>
	<?php endif; ?>

	<article class="l-content l-content--inline">
			<h1>
				<?php echo esc_html($title); ?>
			</h1>
			<?php if ($composer || $bpm || $music_key || $time):  //作曲者,bpm,コード,再生時間があれば ?>
				<ul class="l-cluster c-single-creation__labels ">
					<?php if ($composer): ?>
						<li class="c-single-creation__label">Composer：<?php echo esc_html($composer); ?></li>
					<?php endif; ?>
					<?php if ($bpm): ?>
						<li class="c-single-creation__label">BPM：<?php echo esc_html($bpm); ?></li>
						<?php endif; ?>
					<?php if ($music_key): ?><li class="c-single-creation__label">Key：<?php echo esc_html($music_key); ?></li>
						<?php endif; ?>
					<?php if ($time): ?><li class="c-single-creation__label">Time：<?php echo esc_html($time); ?></li><?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php if ($desc): //共通の説明文があれば ?>
				<div class="c-text c-single-creation__text">
					<?php echo wp_kses_post($desc); ?>
				</div>
			<?php endif; ?>
			<?php if ($video_id): //youtubeの動画があれば ?>
				<h3 class="c-media-badge c-media-badge--youtube">
					<img src="<?= get_template_directory_uri(); ?>/assets/img/creation/single/youtube.svg" alt="YouTube icon">
					<span class="c-media-badge__title">YouTube</span>
				</h3>
				<div
					class="c-video js-youtube"
					data-video-id="<?= esc_attr($video_id); ?>"
				>
				<img
					src="https://img.youtube.com/vi/<?= esc_attr($video_id); ?>/hqdefault.jpg"
					srcset="
						https://img.youtube.com/vi/<?= esc_attr($video_id); ?>/mqdefault.jpg 320w,
						https://img.youtube.com/vi/<?= esc_attr($video_id); ?>/hqdefault.jpg 480w,
						https://img.youtube.com/vi/<?= esc_attr($video_id); ?>/maxresdefault.jpg 1280w
					"
					sizes="(max-width: 768px) 100vw, 800px"
					alt="<?= esc_attr($title); ?> のサムネイル"
					loading="lazy"
					decoding="async"
				>
			</div>
			<?php endif; ?>
			<?php if ($has_streaming): ?>
				<h3 class="c-media-badge c-media-badge--streaming">
					<img
						src="<?= get_template_directory_uri(); ?>/assets/img/creation/single/icon_streaming.svg"
						alt="Streaming Icon"
						width="100%"
						height="auto"
					>
					<span class="c-media-badge__title">
						Streaming Playback – <?= esc_html($title); ?>
					</span>
				</h3>
				<ul class="c-streaming-card c-streaming-card--streaming">
					<?php if ($tc_url): ?>
					<li class="c-streaming-card__item">
						<a class="c-streaming-card--tunecore" href="<?= esc_url($tc_url); ?>" target="_blank" rel="noopener">
							<figure class="c-streaming-card__img c-streaming-card__img--logo">
								<img src="<?= get_template_directory_uri(); ?>/assets/img/creation/single/tunecore.svg" alt="TuneCore" width="100%" height="auto">
							</figure>
						</a>
					</li>
					<?php endif; ?>
					<?php if ($spotify_url): ?>
					<li class="c-streaming-card__item">
						<a class="c-streaming-card--spotify" href="<?= esc_url($spotify_url); ?>" target="_blank" rel="noopener">
							<figure class="c-streaming-card__img c-streaming-card__img--logo">
								<img src="<?= get_template_directory_uri(); ?>/assets/img/creation/single/spotify.svg" alt="Spotify" width="100%" height="auto">
							</figure>
						</a>
					</li>
					<?php endif; ?>
					<?php if ($apple_url): ?>
					<li class="c-streaming-card__item">
						<a class="c-streaming-card--applemusic" href="<?= esc_url($apple_url); ?>" target="_blank" rel="noopener">
							<figure class="c-streaming-card__img c-streaming-card__img--logo">
								<img src="<?= get_template_directory_uri(); ?>/assets/img/creation/single/apple-music.svg" alt="Apple Music" width="100%" height="auto">
							</figure>
						</a>
					</li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
	</article>
</article>

		<?php
// echo '<pre>';
// var_dump($music);
// echo '</pre>';
		?>