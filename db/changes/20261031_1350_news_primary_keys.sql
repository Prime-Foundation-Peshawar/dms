-- news_posts and news_media must have a real auto-increment id. Without one, new items are saved with id 0,
-- and saving or deleting them fails with "Invalid primary key: 0 is not allowed".
-- Does nothing where the key already exists. The old table is kept as news_posts_before_key.

SET @fix := (SELECT COUNT(*) = 0 FROM information_schema.table_constraints
             WHERE table_schema = DATABASE() AND table_name = 'news_posts' AND constraint_type = 'PRIMARY KEY');

SET @sql := IF(@fix, 'CREATE TABLE `news_posts_pk` LIKE `news_posts`', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Exact copies collapse into one row; rows that differ are all kept.
SET @sql := IF(@fix, 'INSERT INTO `news_posts_pk` SELECT DISTINCT * FROM `news_posts`', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'ALTER TABLE `news_posts_pk` ADD COLUMN `row_no` BIGINT NOT NULL AUTO_INCREMENT UNIQUE', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Different rows sharing an id: the first keeps it, the others get a new one below.
SET @sql := IF(@fix, 'UPDATE `news_posts_pk` a JOIN `news_posts_pk` b ON a.`id` = b.`id` AND a.`row_no` > b.`row_no` SET a.`id` = 0', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'SELECT COALESCE(MAX(`id`), 0) INTO @next_id FROM `news_posts_pk`', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'UPDATE `news_posts_pk` SET `id` = (@next_id := @next_id + 1) WHERE `id` = 0', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'ALTER TABLE `news_posts_pk` DROP COLUMN `row_no`, MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'RENAME TABLE `news_posts` TO `news_posts_before_key`, `news_posts_pk` TO `news_posts`', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

-- Nothing refers to media ids, so they can simply be renumbered.
SET @fix := (SELECT COUNT(*) = 0 FROM information_schema.table_constraints
             WHERE table_schema = DATABASE() AND table_name = 'news_media' AND constraint_type = 'PRIMARY KEY');
SET @next_id := 0;

SET @sql := IF(@fix, 'UPDATE `news_media` SET `id` = (@next_id := @next_id + 1)', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;

SET @sql := IF(@fix, 'ALTER TABLE `news_media` MODIFY `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)', 'DO 0');
PREPARE s FROM @sql; EXECUTE s; DEALLOCATE PREPARE s;
