<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190505232857 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_profiles table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_profiles` (
				`id` char(12) NOT NULL,
				`user_id` int(11) unsigned NOT NULL,
				`first_name` varchar(64) DEFAULT '',
				`last_name` varchar(64) DEFAULT '',
				`birthday` date DEFAULT NULL,
				`email` varchar(64) DEFAULT '' COMMENT 'Secondary Email',
				`phone` varchar(16) DEFAULT '',
				`heading` varchar(128) DEFAULT '',
				`intro` text,
				`avatar` varchar(64) DEFAULT '',
				`cover` varchar(64) DEFAULT '',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `user_id` (`user_id`),
				CONSTRAINT `tomos_profiles_ibfk_1`
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
			"DROP TABLE IF EXISTS `tomos_profiles`;"
		);
	}
}
