<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190506004227 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_activity table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_activity` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`description` varchar(255) NOT NULL DEFAULT '',
				`ip_address` varchar(64) NOT NULL DEFAULT '',
				`user_agent` text,
				`created_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				KEY `user_id` (`user_id`),
				CONSTRAINT `tomos_activity_ibfk_1`
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
			"DROP TABLE IF EXISTS `tomos_activity`;"
		);
	}
}
