<?php
$title = get_the_title();

$movie = get_field('movie_fields') ?: [];

$video_id = nowone_get_youtube_id(
  $movie['movie_youtube_link'] ?? ''
); // youtubeをwatch IDで表示するために取得

$desc = get_field('creation_description'); // 共通フィールドから取得
//ACFから取得
$dir  = $movie['movie_director'] ?? ''; // ディレクター名
$length  = $movie['movie_length'] ?? '';

?>

<article class="c-single-movie p-creation-single">

<?php if ($video_id): ?>
  <div class="c-video js-youtube" data-video-id="<?= esc_attr($video_id); ?>">
	<img
	  src="https://img.youtube.com/vi/<?= esc_attr($video_id); ?>/hqdefault.jpg"
	  alt="<?= esc_attr($title); ?>"
	  loading="lazy"
	  decoding="async"
	>
  </div>
<?php endif; ?>
<div class="l-center c-single-movie__cautionary-note">画像をタップで再生されます</div>
<article class="l-content l-content--inline">
		<h1><?= esc_html($title); ?></h1>
		<?php if ($dir || $length):  //director,再生時間があれば ?>
			<ul class="l-cluster c-single-movie__labels">
				<?php if ($dir): ?>
				<li class="c-single-movie__label">Director：<?= esc_html($dir); ?></li>
				<?php endif; ?>
				<?php if ($length): ?>
				<li class="c-single-movie__label">Length：<?= esc_html($length); ?></li>
				<?php endif; ?>
			</ul>
		<?php endif; ?>
		  <?php if ($desc): ?>
		<div class="c-text c-single-movie__text"><?= wp_kses_post($desc); ?></div>
		  <?php endif; ?>
	</article>
</article>
<?php
// $movie = get_field('movie_fields');
// echo '<pre>'; var_dump($movie); echo '</pre>';
?>