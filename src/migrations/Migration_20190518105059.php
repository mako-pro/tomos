<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190518105059 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_images table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_images` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`path` varchar(60) NOT NULL,
				`title` varchar(128) NOT NULL DEFAULT '',
				`text` text,
				`file_name` varchar(60) NOT NULL DEFAULT '',
				`file_type` varchar(25) NOT NULL DEFAULT '',
				`file_ext` varchar(4) NOT NULL DEFAULT '',
				`file_size` int(8) unsigned DEFAULT '0',
				`orient` enum('land','port') DEFAULT 'land',
				`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `path` (`path`),
				KEY `user_id` (`user_id`),
				CONSTRAINT `tomos_images_ibfk_1`
					FOREIGN KEY (`user_id`)
					REFERENCES `users` (`id`)
					ON DELETE CASCADE
					ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;"
		);
	}

	/**
	 * Reverts the database changes.
	 */
	public function down(): void
	{
		$this->getConnection()->query
		(
			"DROP TABLE IF EXISTS `tomos_images`;"
		);
	}

}
