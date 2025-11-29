<?php

//ウィジェット
// register_sidebar(1);
// register_sidebar(ranking);
//カスタムメニュー
register_nav_menus(array(
'navigation' => ' ナビゲーションバー ',
'subnavigation' => ' サブナビゲーションバー '
));

///////////////////////////////////////////////////////////
//管理画面関連
//本体のアップデート通知を非表示
// add_filter('pre_site_transient_update_core', create_function('$a', "return  null;"));
//プラグイン更新通知を非表示
remove_action( 'load-update-core.php', 'wp_update_plugins' );
// add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );
///////////////////////////////////////////////////////////

///////////////////////////////////////////////初期データ削除
//前の記事と後の記事のURL(rel="next"、rel="prev")を削除
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');

//WordPressのバージョン情報を削除
remove_action('wp_head', 'wp_generator');
////////////////////////////////////////////////


///////////////////////////////////////////////////////////
//画像のサイズ値投稿削除
add_filter( 'post_thumbnail_html', 'remove_width_attribute', 10 );
add_filter( 'image_send_to_editor', 'remove_width_attribute', 10 );

function remove_width_attribute( $html ) {
  $html = preg_replace( '/(width|height)="\d*"\s/', "", $html );
  return $html;
}
///////////////////////////////////////////////////////////

// functions.phpに記述
function test_resize_dimensions( $first, $orig_w, $orig_h, $dest_w, $dest_h, $crop ){

	if( false ) return $first;

	if ( $crop ) {
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if ( !$new_w ) {
			$new_w = intval($new_h * $aspect_ratio);
		}

		if ( !$new_h ) {
			$new_h = intval($new_w / $aspect_ratio);
		}

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		// ↓もとの設定
		//$s_x = floor( ($orig_w - $crop_w) / 2 ); // 切抜き時のX起点
		//$s_y = floor( ($orig_h - $crop_h) / 2 ); // 切抜き時のY起点
		// 左上にしたい場合
		//$s_x = 0;
		//$s_y = 0;
		// 右上にしたい場合
		$s_x = floor( ($orig_w - $crop_w) );
		$s_y = 0;

	} else {
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
	}

	// if the resulting image would be the same size or larger we don't want to resize it
	if ( $new_w >= $orig_w && $new_h >= $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'test_resize_dimensions', 10, 6 );


//++++++++++++++++++++++++++++++++++++++
//アイキャッチ
add_theme_support('post-thumbnails');

//基本サイズ
set_post_thumbnail_size(1280,1024,true);
//worksサムネイル画像サイズ
add_image_size('works_thumb_img',350,300,true);
//worksメイン画像サイズ
add_image_size('works_main_img',750,700,true);
//worksサブ画像サイズ
add_image_size('works_sub_img',420,840,true);
//worksサブ画像ポップアップサイズ
add_image_size('works_popup_img',750);

//仕事ギャラリーサムネイルサイズ
add_image_size('archive_works_thumb',768);
//仕事ギャラリー投稿基本サイズ
add_image_size('basic_works_post_size',1350);



//++++++++++++++++++++++++++++++++++++++
/* PRE_GET_POSTS 新着一覧等から保護を削除 */
function customize_main_query ( $query ) {
  if ( ! is_admin() || $query->is_main_query() ) { //管理画面以外 かつ メインクエリー
    if ( $query->is_archive() || is_home() ) { //アーカイブとトップページから削除
      $query->set( 'has_password', false );
    }
  }
}
add_action( 'pre_get_posts', 'customize_main_query' );
// PRE_GET_POSTSにフック（）

//singleページのprev_nextのパスワード保護中記事を削除
add_filter( 'get_next_post_where', 'my_get_post_where', 10, 5 );
add_filter( 'get_previous_post_where', 'my_get_post_where', 10, 5 );
function my_get_post_where( $where, $in_same_term, $excluded_terms ) {
  if ( ! is_admin() && is_single() ) {
    $where .= " AND p.post_password = '' ";
  }
  return $where;
}
//++++++++++++++++++++++++++++++++++++++

//rankingの画像サムネイル変更機能
$GLOBALS["ranking_count"] = 1;
function my_popular_post( $post_html, $p, $instance ){
    // 投稿IDの取得
    $post_id = $p->id;
    // 投稿日時
    $date = get_the_date('Y.m.d',$post_id);
    // タイトル
    $title = get_the_title($post_id);
    // パーマリンク
    $permalink = get_the_permalink($post_id);
    // 画像（Advanced Custom Fieldsを使用）
    if ( get_field('square_img',$post_id) ) {
        // $image = get_field('square_img',$post_id);
        // $imgurl = wp_get_attachment_url( $image );
        $image = get_field('square_img',$post_id);
        $imgurl = wp_get_attachment_image_src( $image,'90' );
        $imghtml = '<img src="'.$imgurl[ 0 ].'" alt="" width="90">';
    } elseif(has_post_thumbnail()) {
        // $thumbnail_id = get_post_thumbnail_id( $p->id);
        // $imgurl = wp_get_attachment_url( $thumbnail_id, );
        $thumbnail_id = get_post_thumbnail_id( $p->id);
        $imgurl = wp_get_attachment_image_src( $thumbnail_id,'sidebar_thumb_size' );
        $imghtml = '<img src="'.$imgurl[ 0 ].'" alt="" width="90">';
    }
     else {
        $imgurl = get_bloginfo('template_url') . '/images/common/no_image_side.jpg';
        $imghtml = '<img src="'.$imgurl[ 0 ].'" alt="" width="90">';
    }
    // ランキング
    $ranking = $GLOBALS["ranking_count"]++;
// ランキング体裁出力
echo <<< EOF
<li>
  <a href="{$permalink}">
    <div class="ranking_thumb">{$imghtml}</div>
    <div class="ranking_title">{$title}</div>
  </a>
</li>
EOF;
}
add_filter( 'wpp_post', 'my_popular_post', 10, 3 );
//++++++++++++++++++++++++++++++++++++++

//画像リンク削除
add_filter( 'the_content', 'attachment_image_link_remove_filter' );
function attachment_image_link_remove_filter( $content ) {
 $content =  preg_replace(  array('{<a(.*?)(wp-att|wp-content/uploads)[^>]*><img}',  '{ wp-image-[0-9]*" /></a>}'), array('<img','" />'),  $content  );
 return $content;
 }
/* as seen on http://wpmu.org/how-to-remove-links-from-wordpress-images/ */

function my_remove_img_attr($html, $id, $alt, $title, $align, $size){

	$html = preg_replace('/ width=&quot;d+&quot;/', '', $html);
	$html = preg_replace('/ height=&quot;d+&quot;/', '', $html);
	$html = preg_replace('/ class=&quot;.+&quot;/', '', $html);
	$html = preg_replace('/ title=&quot;.+&quot;/', '', $html);
return $html;
}
add_action( 'get_image_tag', 'my_remove_img_attr', 1 ,6);

// Get the featured image URL
function get_featured_image_url() {
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id,'main_post_image', true);
    echo $image_url[0];
}

?>