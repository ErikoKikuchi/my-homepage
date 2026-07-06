-- MYSQL_DATABASE=thinkmotion_db は docker-compose.yml の environment で自動生成
-- 残り2つを追加作成

CREATE DATABASE IF NOT EXISTS thinkmotion_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS client_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS code_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- laravel_user に全 DB へのアクセス権を付与
GRANT ALL PRIVILEGES ON thinkmotion_db.*     TO 'laravel_user'@'%';
GRANT ALL PRIVILEGES ON client_db.*    TO 'laravel_user'@'%';
GRANT ALL PRIVILEGES ON code_db.*       TO 'laravel_user'@'%';

FLUSH PRIVILEGES;
