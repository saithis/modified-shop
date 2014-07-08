

UPDATE database_version SET version = 'MOD_1.0.6.1';

ALTER TABLE `categories` ADD `listing_module` VARCHAR(64) NOT NULL DEFAULT '' AFTER `group_permission_3`;