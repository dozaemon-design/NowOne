<?php
/**
 * Archive Music Template
 */
 
$type = $args['type'] ?? 'music';
?>
<p class="u-visually-hidden">
  NowOne の音楽作品一覧。Electronic Musicを中心に発表しています。お聴きいただければ幸いです。
</p>
<?php get_template_part('template-parts/creation/archive/list', '', ['type' => $type]); ?>
