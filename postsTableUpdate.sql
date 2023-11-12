START TRANSACTION;
ALTER TABLE posts RENAME COLUMN title TO product_name;
ALTER TABLE posts ADD COLUMN product_type varchar(20) AFTER user_id;
ALTER TABLE posts DROP COLUMN `type`;
ALTER TABLE posts
DROP COLUMN `image`,
DROP COLUMN `harvest_method`,
DROP COLUMN `harvest_date`,
DROP COLUMN `storage_conditions`,
DROP COLUMN `treatments_used`,
DROP COLUMN `pesticides_used`,
DROP COLUMN `price`,
DROP COLUMN `location`
;
ALTER TABLE posts
ADD COLUMN product_image VARCHAR(30),
ADD COLUMN `description` TEXT NULL,
ADD COLUMN varietal_information VARCHAR(255) NULL,
ADD COLUMN origin VARCHAR(255) NULL,
ADD COLUMN health VARCHAR(255) NULL,
ADD COLUMN harvest_method VARCHAR(255) NULL,
ADD COLUMN production_method VARCHAR(255) NULL,
ADD COLUMN breeding_method VARCHAR(255) NULL,
ADD COLUMN harvest_date DATE NULL,
ADD COLUMN production_date DATE NULL,
ADD COLUMN storage_conditions VARCHAR(255) NULL,
ADD COLUMN preservation_practices VARCHAR(255) NULL,
ADD COLUMN packaging VARCHAR(255) NULL,
ADD COLUMN preharvest_treatments VARCHAR(255) NULL,
ADD COLUMN postharvest_treatments VARCHAR(255) NULL,
ADD COLUMN vaccination_info VARCHAR(255) NULL,
ADD COLUMN treatment_info VARCHAR(255) NULL,
ADD COLUMN price INT,
ADD COLUMN location VARCHAR(50) NULL
;
COMMIT;