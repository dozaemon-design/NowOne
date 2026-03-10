<?php
/**
 * Archive Artwork Template
 */

$type = $args['type'] ?? 'artwork';
?>
<p class="u-visually-hidden">
  NowOne のArtwork作品一覧。イラストを中心に発表しています。まだまだなので、ご助言等いただければ幸いです。
</p>
<?php get_template_part('template-parts/creation/archive/list', '', ['type' => $type]); ?>
