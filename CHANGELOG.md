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

- 予約機能
- マイページ
