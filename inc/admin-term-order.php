<?php
/**
 * Term ordering (Admin UI + Front sorting)
 *
 * 対象:
 * - genre_*（creation系ジャンル）
 * - portfolio_genre
 *
 * 方針:
 * - termmeta に数値 order を保存
 * - 管理画面: 一覧に列 + クイック編集 + 編集画面に入力欄
 * - フロント/管理画面: get_terms の結果を「親ごとに子を並べ替え」する
 */

define('NOWONE_TERM_ORDER_META_KEY', '_nowone_term_order');

function nowone_is_orderable_taxonomy(string $taxonomy): bool {
  // creation系: genre_music / genre_movie / genre_artwork ...（将来追加も含む）
  if (str_starts_with($taxonomy, 'genre_')) {
    return true;
  }
  // portfolio系: portfolio_genre / portfolio_role / portfolio_tool ...（将来追加も含む）
  if (str_starts_with($taxonomy, 'portfolio_')) {
    return true;
  }
  return false;
}

function nowone_get_term_order_value(int $term_id): int {
  $value = get_term_meta($term_id, NOWONE_TERM_ORDER_META_KEY, true);
  if ($value === '' || $value === null) {
    return 0;
  }
  return (int) $value;
}

/**
 * Register taxonomy-specific admin hooks when taxonomy is registered.
 */
add_action('registered_taxonomy', function (string $taxonomy) {
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    return;
  }

  // Add form (new term)
  add_action("{$taxonomy}_add_form_fields", function () {
    ?>
    <div class="form-field term-nowone-order-wrap">
      <label for="nowone_term_order">表示順</label>
      <input name="nowone_term_order" id="nowone_term_order" type="number" step="1" min="0" value="">
      <p class="description">数字が小さいほど先頭に表示されます（親term内の並びに反映）。</p>
    </div>
    <?php
  });

  // Edit form (existing term)
  add_action("{$taxonomy}_edit_form_fields", function (WP_Term $term) {
    $value = nowone_get_term_order_value((int) $term->term_id);
    ?>
    <tr class="form-field term-nowone-order-wrap">
      <th scope="row"><label for="nowone_term_order">表示順</label></th>
      <td>
        <input name="nowone_term_order" id="nowone_term_order" type="number" step="1" min="0" value="<?php echo esc_attr((string) $value); ?>">
        <p class="description">数字が小さいほど先頭に表示されます（親term内の並びに反映）。</p>
      </td>
    </tr>
    <?php
  });

  // List table column
  add_filter("manage_edit-{$taxonomy}_columns", function (array $columns) {
    // "count" 的な雰囲気の数字列として追加
    $new = [];
    foreach ($columns as $key => $label) {
      $new[$key] = $label;
      if ($key === 'posts') {
        $new['nowone_order'] = '順';
      }
    }
    if (!isset($new['nowone_order'])) {
      $new['nowone_order'] = '順';
    }
    return $new;
  });

  add_filter("manage_{$taxonomy}_custom_column", function ($out, string $column_name, int $term_id) {
    if ($column_name !== 'nowone_order') {
      return $out;
    }
    $value = nowone_get_term_order_value($term_id);
    return ''
      . '<input type="number" class="nowone-term-order-input" data-term-id="' . esc_attr((string) $term_id) . '" value="' . esc_attr((string) $value) . '" step="1" min="0" style="width:5.5em;">'
      . '<span class="nowone-term-order" data-order="' . esc_attr((string) $value) . '" style="display:none;">' . esc_html((string) $value) . '</span>';
  }, 10, 3);

  // After table: save button (list inline editing)
  add_action("after-{$taxonomy}-table", function () use ($taxonomy) {
    if (!current_user_can(get_taxonomy($taxonomy)->cap->edit_terms)) {
      return;
    }
    ?>
    <div class="nowone-term-order-actions" style="margin-top:12px;">
      <button type="button" class="button button-primary nowone-term-order-save" disabled>順序を保存</button>
      <span class="spinner" style="float:none; margin:0 0 0 8px;"></span>
      <span class="description" style="margin-left:8px;">保存後に再読み込みして並び順を反映します。</span>
    </div>
    <?php
  });
});

/**
 * Quick Edit UI: render input for our custom column.
 * Hooked by core when a non-core column exists.
 */
add_action('quick_edit_custom_box', function (string $column_name, string $screen, string $taxonomy) {
  if ($screen !== 'edit-tags') {
    return;
  }
  if ($column_name !== 'nowone_order') {
    return;
  }
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    return;
  }
  ?>
  <fieldset>
    <div class="inline-edit-col">
      <label>
        <span class="title">表示順</span>
        <span class="input-text-wrap">
          <input type="number" name="nowone_term_order" class="ptitle" value="" step="1" min="0">
        </span>
      </label>
    </div>
  </fieldset>
  <?php
}, 10, 3);

/**
 * Quick Edit JS: populate our field from the row.
 */
add_action('admin_footer-edit-tags.php', function () {
  $screen = function_exists('get_current_screen') ? get_current_screen() : null;
  if (!$screen || empty($screen->taxonomy)) {
    return;
  }
  $taxonomy = (string) $screen->taxonomy;
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    return;
  }
  $nonce = wp_create_nonce('nowone_term_order');
  ?>
  <script>
    (function($){
      if (typeof inlineEditTax === 'undefined') return;
      const NOWONE_TERM_ORDER = {
        taxonomy: <?php echo wp_json_encode($taxonomy); ?>,
        nonce: <?php echo wp_json_encode($nonce); ?>,
        ajaxUrl: (typeof ajaxurl !== 'undefined') ? ajaxurl : ''
      };

      const dirty = new Map();
      const markDirty = function(termId, value){
        if (!termId) return;
        dirty.set(String(termId), String(value ?? 0));
        $('.nowone-term-order-save').prop('disabled', false);
      };

      $(document).on('change', '.nowone-term-order-input', function(){
        const termId = parseInt($(this).data('term-id'), 10);
        markDirty(termId, $(this).val());
      });

      const saveOne = function(termId, value){
        return $.post(NOWONE_TERM_ORDER.ajaxUrl, {
          action: 'nowone_update_term_order',
          taxonomy: NOWONE_TERM_ORDER.taxonomy,
          term_id: termId,
          order: value,
          _ajax_nonce: NOWONE_TERM_ORDER.nonce
        });
      };

      const saveAll = async function(){
        if (!dirty.size) return;
        const $wrap = $('.nowone-term-order-actions').first();
        const $btn = $wrap.find('.nowone-term-order-save');
        const $spinner = $wrap.find('.spinner');

        $btn.prop('disabled', true);
        $spinner.addClass('is-active');

        try {
          for (const [termId, value] of dirty.entries()) {
            const res = await saveOne(termId, value);
            if (!res || !res.success) {
              throw new Error((res && res.data && res.data.message) ? res.data.message : '保存に失敗しました');
            }
            const order = res.data && typeof res.data.order !== 'undefined' ? res.data.order : value;
            const $row = $('#tag-' + termId);
            $row.find('.nowone-term-order').attr('data-order', order).text(order);
            $row.find('.nowone-term-order-input').val(order);
          }
          dirty.clear();
          location.reload();
        } catch (e) {
          window.alert(e && e.message ? e.message : '保存に失敗しました');
          $btn.prop('disabled', false);
        } finally {
          $spinner.removeClass('is-active');
        }
      };

      $(document).on('click', '.nowone-term-order-save', function(){
        saveAll();
      });

      const _edit = inlineEditTax.edit;
      inlineEditTax.edit = function(id){
        _edit.apply(this, arguments);
        let termId = 0;
        if (typeof id === 'object') {
          termId = parseInt(this.getId(id), 10);
        } else {
          termId = parseInt(id, 10);
        }
        if (!termId) return;
        const $row = $('#tag-' + termId);
        const order = $row.find('.nowone-term-order-input').val() ?? $row.find('.nowone-term-order').data('order');
        $('#inline-edit').find('input[name="nowone_term_order"]').val(order ?? 0);
      };
    })(jQuery);
  </script>
  <?php
});

/**
 * Ajax: update order from inline list UI.
 */
add_action('wp_ajax_nowone_update_term_order', function () {
  check_ajax_referer('nowone_term_order');

  $taxonomy = isset($_POST['taxonomy']) ? sanitize_text_field(wp_unslash($_POST['taxonomy'])) : '';
  $term_id = isset($_POST['term_id']) ? (int) $_POST['term_id'] : 0;
  $order = isset($_POST['order']) ? (int) sanitize_text_field(wp_unslash($_POST['order'])) : 0;

  if (!$taxonomy || !$term_id) {
    wp_send_json_error(['message' => 'Invalid request'], 400);
  }
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    wp_send_json_error(['message' => 'Not allowed taxonomy'], 403);
  }

  $term = get_term($term_id);
  if (!$term || is_wp_error($term) || $term->taxonomy !== $taxonomy) {
    wp_send_json_error(['message' => 'Term not found'], 404);
  }

  if (!current_user_can(get_taxonomy($taxonomy)->cap->edit_terms) || !current_user_can('edit_term', $term_id)) {
    wp_send_json_error(['message' => 'Forbidden'], 403);
  }

  update_term_meta($term_id, NOWONE_TERM_ORDER_META_KEY, $order);
  wp_send_json_success(['order' => $order]);
});

/**
 * Save handler (add/edit + quick edit).
 */
add_action('created_term', function (int $term_id, int $tt_id, string $taxonomy) {
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    return;
  }
  if (!isset($_POST['nowone_term_order'])) {
    return;
  }
  $value = (int) sanitize_text_field(wp_unslash($_POST['nowone_term_order']));
  update_term_meta($term_id, NOWONE_TERM_ORDER_META_KEY, $value);
}, 10, 3);

add_action('edited_term', function (int $term_id, int $tt_id, string $taxonomy) {
  if (!nowone_is_orderable_taxonomy($taxonomy)) {
    return;
  }
  if (!isset($_POST['nowone_term_order'])) {
    return;
  }
  $value = (int) sanitize_text_field(wp_unslash($_POST['nowone_term_order']));
  update_term_meta($term_id, NOWONE_TERM_ORDER_META_KEY, $value);
}, 10, 3);

/**
 * Front/Admin: sort terms within each parent by our meta.
 * - orderby 指定が明示されている場合は邪魔しない
 */
add_filter('get_terms', function ($terms, array $taxonomies, array $args) {
  if (empty($terms) || !is_array($terms)) {
    return $terms;
  }
  if (empty($taxonomies)) {
    return $terms;
  }

  $target = false;
  foreach ($taxonomies as $tax) {
    if (nowone_is_orderable_taxonomy((string) $tax)) {
      $target = true;
      break;
    }
  }
  if (!$target) {
    return $terms;
  }

  // Explicit orderby (count, slug, etc.) is respected.
  $orderby = $args['orderby'] ?? '';
  if ($orderby === 'include') {
    return $terms;
  }
  if ($orderby && !in_array($orderby, ['name', 'term_id', 'id', 'include'], true)) {
    return $terms;
  }

  // Only when WP_Term objects are returned.
  if (!isset($terms[0]) || !is_object($terms[0]) || !($terms[0] instanceof WP_Term)) {
    return $terms;
  }

  $terms_by_id = [];
  foreach ($terms as $t) {
    if ($t instanceof WP_Term) {
      $terms_by_id[(int) $t->term_id] = $t;
    }
  }
  if (!$terms_by_id) {
    return $terms;
  }

  $order_map = [];
  foreach ($terms_by_id as $id => $t) {
    $order_map[$id] = nowone_get_term_order_value($id);
  }

  $children_map = [];
  foreach ($terms_by_id as $id => $t) {
    $parent = (int) $t->parent;
    $children_map[$parent][] = $t;
  }

  $sort_siblings = function (&$list) use ($order_map) {
    usort($list, function (WP_Term $a, WP_Term $b) use ($order_map) {
      $oa = $order_map[(int) $a->term_id] ?? 0;
      $ob = $order_map[(int) $b->term_id] ?? 0;
      if ($oa !== $ob) {
        return $oa <=> $ob;
      }
      $na = (string) $a->name;
      $nb = (string) $b->name;
      $cmp = strnatcasecmp($na, $nb);
      if ($cmp !== 0) {
        return $cmp;
      }
      return ((int) $a->term_id) <=> ((int) $b->term_id);
    });
  };

  foreach ($children_map as &$list) {
    $sort_siblings($list);
  }
  unset($list);

  $out = [];
  $visited = [];

  $walk = function (int $parent_id) use (&$walk, &$out, &$visited, $children_map) {
    if (empty($children_map[$parent_id])) {
      return;
    }
    foreach ($children_map[$parent_id] as $term) {
      $id = (int) $term->term_id;
      if (isset($visited[$id])) {
        continue;
      }
      $visited[$id] = true;
      $out[] = $term;
      $walk($id);
    }
  };

  // Roots: parent=0 or parent not included in result set.
  $root_ids = [];
  foreach ($terms_by_id as $id => $t) {
    $parent = (int) $t->parent;
    if ($parent === 0 || !isset($terms_by_id[$parent])) {
      $root_ids[$parent] = true;
    }
  }

  // First traverse true root (0), then other "dangling roots".
  $walk(0);
  foreach (array_keys($root_ids) as $parent_id) {
    if ($parent_id === 0) {
      continue;
    }
    $walk((int) $parent_id);
  }

  // Append anything left (safety).
  foreach ($terms_by_id as $id => $t) {
    if (!isset($visited[$id])) {
      $out[] = $t;
    }
  }

  return $out;
}, 10, 3);

/**
 * Admin: show full term set for ordering UI.
 * - edit-tags.php の一覧で「順」入力→保存→並び替え を安定させるため、対象taxは全件取得。
 * - meta_key を query に入れると、meta未保存termが落ちる可能性があるため使わない。
 */
add_action('pre_get_terms', function (WP_Term_Query $query) {
  if (!is_admin()) {
    return;
  }

  $screen = function_exists('get_current_screen') ? get_current_screen() : null;
  if (!$screen || ($screen->base ?? '') !== 'edit-tags') {
    return;
  }

  $taxonomies = $query->query_vars['taxonomy'] ?? [];
  if (empty($taxonomies)) {
    return;
  }
  $taxonomies = (array) $taxonomies;

  $target_taxonomy = null;
  foreach ($taxonomies as $tax) {
    $tax = (string) $tax;
    if (nowone_is_orderable_taxonomy($tax)) {
      $target_taxonomy = $tax;
      break;
    }
  }
  if (!$target_taxonomy) {
    return;
  }

  // Respect explicit ordering.
  $orderby = $query->query_vars['orderby'] ?? '';
  if (!empty($orderby)) {
    return;
  }

  // Coreは階層taxのみ全件取得に切り替えるため、非階層も合わせて全件取得に寄せる。
  $query->query_vars['number'] = 0;
  $query->query_vars['offset'] = 0;
});
