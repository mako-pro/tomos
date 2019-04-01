<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190401115219 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create groups table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `groups` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`name` varchar(32) COLLATE utf8_general_ci NOT NULL,
				`description` varchar(255) DEFAULT '',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `groups_name_unique` (`name`)
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
			"DROP TABLE IF EXISTS `groups`;"
		);
	}
}
