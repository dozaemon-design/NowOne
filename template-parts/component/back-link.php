<?php
/**
 * Back Link component
 *
 * Args:
 * - href  (string) required
 * - label (string) optional (default: 一覧に戻る)
 */

$href = (string) ($args['href'] ?? '');
$label = (string) ($args['label'] ?? '一覧に戻る');

if ($href === '') {
  return;
}
?>

<ul class="c-back-link">
  <li class="c-back-link__item">
    <a class="c-back-link__link" href="<?php echo esc_url($href); ?>">
      <?php echo esc_html($label); ?>
    </a>
  </li>
</ul>
