-- MYSQL_DATABASE=study_logs_db は docker-compose.yml の environment で自動生成
-- 残り3つを追加作成

CREATE DATABASE IF NOT EXISTS reservation_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS training_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS seed_of_thought_db
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- laravel_user に全 DB へのアクセス権を付与
GRANT ALL PRIVILEGES ON study_logs_db.*     TO 'laravel_user'@'%';
GRANT ALL PRIVILEGES ON reservation_db.*    TO 'laravel_user'@'%';
GRANT ALL PRIVILEGES ON training_db.*       TO 'laravel_user'@'%';
GRANT ALL PRIVILEGES ON seed_of_thought_db.* TO 'laravel_user'@'%';

FLUSH PRIVILEGES;
