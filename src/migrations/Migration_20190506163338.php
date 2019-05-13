<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190506163338 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_experiences table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_experiences` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`company` varchar(128) DEFAULT '',
				`position` varchar(128) DEFAULT '',
				`city` varchar(128) DEFAULT '',
				`description` text,
				`is_current` enum('1','0') DEFAULT '0' COMMENT 'Working is currently',
				`from_date` char(7) NOT NULL,
				`to_date` char(7) NOT NULL,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				KEY `user_id` (`user_id`),
				CONSTRAINT `tomos_experiences_ibfk_1`
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
			"DROP TABLE IF EXISTS `tomos_experiences`;"
		);
	}
}
