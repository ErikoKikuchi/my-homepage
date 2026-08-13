# Changelog

## v0.1.0

- static側をhtml/css/TypeScriptにて構成（node_modulesのみ）

## v0.2.0

- Laravel12環境構築
- docker環境構築(Nginx:1.27, PHP:8.3-fpm, PhpMyAdmin, Redis:7-alpine, MySQL:8.4,npm:10.9.7)
- 複数DBの構築(thinkmotion_db(デフォルト)とclient_db,code_dbのトリプル)
- Notion-api連携にて開発記録環境構築

## v0.3.0

- guard('web','admin')設定
- 認証: Fortifyによるマルチガード、管理者は独自ガード+2FA(pragmarx/google2fa-laravel)
- メール認証

## v0.4.0

- 予約カレンダー〜予約フローの本実装
  ReservationAvailabilityService、3状態カレンダー（available/contact_only/full）
  LessonSlot単位の時間選択、DB::transaction+lockForUpdate()による排他制御

- マイページ拡充（次回予約、回数券残数、キャンセル導線、自主トレログ、LINE連携導線）設計
- キャンセル機能実装（cancelled_at/cancelled_byカラム追加、upComingスコープ修正）
- 交通費等対応の決定（locations.price_addon_per_session）
  管理画面のスコープ確定（スケジュール、カルテ、会計（青色申告CSV）、会場マスタ、セッション一覧）

## v0.5.0

- 認証まわりのマルチガード・マルチセクション対応
  - `login_from`/`pilates_login_from`/`thinkmotion_login_from`のセクション別セッションキー導入（`redirect()->intended()`はマルチガード衝突のため不採用）
  - `bootstrap/app.php`の`redirectGuestsTo()`でセクション別ゲストリダイレクト
- インフラ構成の統合
  - nginxのserver blockを4つから`localhost`1つ（パスベースルーティング）に集約
  - `.php`ネストによる内部リライト漏れ、絶対パス参照によるstatic distへのフォールスルーを修正
  - `THINKMOTION_URL`/`PILATES_URL`をサブドメイン方式から`localhost`パス方式に変更
  - Windows hostsファイル整理

## v0.6.0

- 管理者2FA認証フローの本実装（login→setup→verify→dashboard）
  - セクション別セッションキー、プレフィックス付きルート（`/pilates/admin/...`、`/thinkmotion/admin/...`）
  - `$request->attributes`でセクションを受け取る単一コントローラ構成、3階層Bladeインヘリタンス
  - `AdminSectionMiddleware`による`sections`belongsToMany所属チェック
- `ReservationPolicy`実装（IDOR対策）

## v0.7.0

- OWASP Top 10に基づくセキュリティ監査を実施
  - 全ログインルートへのレート制限、2FAのレート制限を追加
  - IDOR対策（`ReservationPolicy`）
- アーキテクチャ方針を確定
  - 管理画面（Blade）→React化（Inertia等を想定、認証必須・動的データ中心のため）
  - 静的サイト（LP的部分）→Next.js化（SEO・ビルド最適化のため）
  - React/Next.jsともにTypeScriptで統一する方針
  - 静的サイトのビルド・デプロイパイプラインはこの終着点を見据えて設計予定
- 秘匿情報管理方針: `.env`（`.gitignore`対象）に加え、Bitwarden等へのバックアップを運用

## v0.8.0

- OWASP Top 10監査の是正完了（1〜8番目、9番目の静的サイトパイプラインはNext.js移行時に対応予定として保留）
- 管理者2FAの不具合修正（`Admin2FAMiddleware`、`AdminTwoFactorController::verify()`）
- 会場管理CRUD実装
- レッスンテンプレート・レッスンスロットCRUD実装（`PilatesAdminLessonTemplateController`、`PilatesAdminLessonSlotController`）
  - UUID主キー、forestカラートークンによるデザインシステム、FormRequestベースの日本語バリデーションメッセージ
- 予約フロー実装（3経路: Googleフォーム、ThinkMotion共通登録画面経由の通常登録、管理者による電話予約代行登録）
  - 仮登録ユーザー機能（氏名・電話番号のみ、パスワードはランダム生成、メール任意）
  - `StoreReservationRequest::prepareForValidation()`での電話番号正規化
  - グループレッスンは代表者予約制(最大4名)、予約サイト上で複数人数選択時はグループレッスンのみ対応と明記済み
- ThinkMotion設計確定
  - 3本柱構成（症例検討／Readings／My Studies）
  - Readings機能: 3層データモデル（`readings`→`reading_units`→`reading_extracts`）による抽出と解釈の分離
  - book-spineビジュアルモチーフ、forest greenパレット、スタンスベースの`purpose`タグ
  - ポリモーフィックいいね機構、Readingsへのコメント機能なし
  - 臨床ドメインを超えた抽象化はピラティスセクション完成後に先送り
- フロントエンド方針の確定（案A）: 管理画面→React/Inertia.js（TypeScript）、静的サイト→Next.js（TypeScript）、両者間の型共有パッケージは不要と判断
- 認証・認可の設計思想に関する技術ブログ記事を公開
