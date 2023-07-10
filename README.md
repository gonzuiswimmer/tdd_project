# 手順

-前提-
クローン（コピー）後、src/php ディレクトリは削除する
.env のプロジェクト名「testapp0330」は好きに変えてください

1. docker-compose.yml をもとに docker build で docker を立ち上げ（いらないコンテナは削除。db1,pgadmin,phpmyadmin などはいらない）
2. docker up -d でコンテナを作成。docker compose exec php bash でコンテナの中に入る
3. vendor フォルダが自動生成されている場合は削除し、composer create-project laravel/laravel:^version (project-name) --prefer-dist で laravel プロジェクトを作成する
   ※vendor フォルダが削除できない場合、composer clear-cache でキャッシュを削除後、composer install でインストールし直すとうまくいく
4. アプリケーションキーの作成や(cd ../)chmod -R 777 html/ などで localhost(laravel)につながることを確認
5. DB と接続する

### ≪ プロジェクトの yml ファイル配下で mysql コンテナ内に入る ≫

docker compose exec db2 bash

### ≪ ログインする ≫

mysql -u root -prootpass

### DB の作成コマンド

CREATE DATABASE database_name DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

### データベースを操作するユーザの作成(初回のみ。基本的に省略可)

CREATE USER 'laraveluser' IDENTIFIED BY 'laravelpass';

### 権限の付与

GRANT ALL PRIVILEGES ON database_name.\* TO 'laraveluser';
use database_name;

6. docker の ./env ファイルに DB_HOST、 DB 名、User、Pass を記述。php artisan migrate を実行し、DB と接続できているか確認する
