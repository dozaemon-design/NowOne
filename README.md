# NowOne
NowOneHome

# Deploy Update
deploy tests
2025/11/30 Github Update to Deploy.yml for github Actions
2025/11/28  Github Update to AutoDeploy

# Google GTM
wp-congigに記載
新規を作る時には要注意！
define('GTM_ID', 'GTM-XXXXXXX');
部分を変えること。

# Data Streaming Setting
管理画面
 └ ACF: creation_type_ui（select）
      ↓
 保存時フック
      ↓
 creation_type（taxonomy）に反映
      ↓
 URL / テンプレート / クエリ判定に使用

# System Design
theme/
├── archive-creation.php        ← 投稿タイプの入口
├── single-creation.php         ← シングルの入口
├── taxonomy-creation_type.php  ← taxonomyの入口
│
├── template-parts/
│   ├── creation/
│   │   ├── card.php
│   │   ├── loop.php
│   │   ├── header.php
│   │   └── footer.php
│   │
│   ├── archive/
│   │   ├── music.php
│   │   ├── movie.php
│   │   └── artwork.php
│   │
│   ├── single/
│   │   ├── music.php
│   │   ├── movie.php
│   │   └── artwork.php
│   │
│   └── meta/
│       └── ogp.php

# SCSS Setting
foundation（触らない）
  ↓
layout（たまに触る）
  ↓
project（よく触る）
  ↓
component（基本触らない）
  ↓
utility（例外）

component は「見た目」ではなく「意味」で分ける

状態      button    link
hover     視覚変化  下線 or 色
focus-visible   box-shadow  outline
active    押下感    ほぼ不要
disabled  :disabled   作らない

# ACF Field Setting
	•	Creation Type Selector
	•	creation_type_ui（Select）
	•	Conditional Groups
	•	music_fields（条件：creation_type_ui == music）
	•	movie_fields（条件：creation_type_ui == movie）
	•	artwork_fields（条件：creation_type_ui == artwork）

# Creation 拡張手順
## Creation Type 自動同期

creation_type は管理画面では選択しない。
genre_* taxonomy に基づいて自動付与される。

### 追加方法
- genre taxonomy を追加
- post-types.php の $map に1行追加

例：
'genre_music' => 'music'

# Portfolio 拡張手順
全体方針
	•	Portfolio は 1 CPT で一元管理
	•	種別（Web / App / bnr / printing）は taxonomy で分類
	•	1作品 = 1ジャンル
	•	URL / 表示分岐は WordPress 標準挙動を尊重
	•	手動 rewrite・複雑な分岐は最小限

2. データ構造
■ Custom Post Type
portfolio

■ Taxonomy（ジャンル）
portfolio_genre
想定ターム：
	•	web
	•	app
	•	bnr
	•	printing

ルール：
	•	1投稿につき1タームのみ
	•	UI では「ジャンル」として扱う

3. URL 設計
全体一覧
/portfolio/     archive-portfolio.php
ジャンル一覧
/portfolio/bnr/     taxonomy-portfolio_genre.php
詳細
/portfolio/bnr/slug/      single-portfolio.php

4. テンプレート構成
theme/
├─ archive-portfolio.php
├─ taxonomy-portfolio_genre.php
├─ single-portfolio.php
└─ template-parts/
    └─ portfolio/
        ├─ card.php        // 一覧カード
        ├─ header.php      // ジャンル名・説明
        └─ content.php     // 詳細本文

5. ACF の考え方
■ 原則
	•	表示用フィールド と 実データ は分離
	•	UI は人間向け
	•	判定・URL・分岐は機械向け

	•	表示用：portfolio_genre_ui
	•	実データ：portfolio_genre（taxonomy）

👉 「表示用と裏データが違う」


#　拡張時の手順
例：portfolio
	1.	creation_type に term 追加（portfolio）
	2.	creation_type_ui の選択肢に追加
	3.	portfolio_fields（ACF グループ）を作成
	4.	フロントテンプレートに分岐追加

  Portfolio を作る時の判断基準（重要）
	•	Creation と思想が同じ → 統合
	•	表示・入力・責務が違う → post_type 分離


	foundation/
├─ _reset.scss          // ブラウザ差異を消す（最小・安全）
├─ _base.scss           // html, body, 基本要素の初期状態
├─ _space.scss          // space scale（余白の正規値）
├─ _typography.scss     // font-size / line-height scale
├─ _breakpoints.scss    // メディアクエリ用の「値」
├─ _z-index.scss        // 任意（レイヤー管理）
└─ index.scss           // ↑を集約


layout/
├─ _stack.scss     // 縦に積む
├─ _cluster.scss   // 横並び＋折り返し
├─ _switcher.scss  // 一定幅で縦横切替
├─ _center.scss    // 横中央寄せ
├─ _cover.scss     // ヘッダー＋メイン＋フッター
└─ index.scss