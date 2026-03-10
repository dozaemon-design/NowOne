<?php
/**
 * Archive Movie Template
 */

$type = $args['type'] ?? 'movie';
?>
<p class="u-visually-hidden">
  NowOne の映像作品一覧。曲を映像に乗せたものを中心に発表しています。見ていただければ幸いです。
</p>
<?php get_template_part('template-parts/creation/archive/list', '', ['type' => $type]); ?>
