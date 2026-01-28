<?php
/**
 * Pagination Component
 * 
 * 使用方法:
 * get_template_part('template-parts/creation/component/pagenation', '', [
 *   'query' => $query (WP_Query object)
 *   'paged' => $paged (current page number)
 * ]);
 */

// 変数取得
$query = $args['query'] ?? null;
$paged = $args['paged'] ?? 1;

// クエリがない場合はスキップ
if (!$query || !$query->have_posts()) {
  return;
}

// ページ数が1ページ以下の場合はページネーション表示不要
if ($query->max_num_pages <= 1) {
  return;
}
?>

<!-- ページネーション -->
<nav class="c-pagination" role="navigation" aria-label="ページネーション">
  <?php
    echo paginate_links([
      'total'     => $query->max_num_pages,
      'current'   => $paged,
      'prev_text' => '&laquo; 前へ',
      'next_text' => '次へ &raquo;',
      'type'      => 'list',
    ]);
  ?>
</nav>

<?php wp_reset_postdata(); ?>