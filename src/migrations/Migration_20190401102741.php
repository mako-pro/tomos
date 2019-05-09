<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190401102741 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create users table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `users` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				`ip` varchar(255) NOT NULL,
				`username` varchar(255) NOT NULL,
				`email` varchar(255) NOT NULL,
				`password` varchar(255) NOT NULL,
				`action_token` char(64) DEFAULT '',
				`access_token` char(64) DEFAULT '',
				`activated` tinyint(1) NOT NULL DEFAULT 0,
				`banned` tinyint(1) NOT NULL DEFAULT 0,
				`failed_attempts` int(11) NOT NULL DEFAULT '0',
				`last_fail_at` datetime DEFAULT NULL,
				`locked_until` datetime DEFAULT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `username` (`username`),
				UNIQUE KEY `email` (`email`),
				UNIQUE KEY `action_token` (`action_token`),
				UNIQUE KEY `access_token` (`access_token`)
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
			"DROP TABLE IF EXISTS `users`;"
		);
	}
}
