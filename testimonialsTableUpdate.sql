ALTER TABLE `testimonials` ADD COLUMN phone varchar(10);
ALTER TABLE `testimonials` DROP COLUMN user_id;
ALTER TABLE `testimonials` ADD COLUMN user_name varchar(50) AFTER id;