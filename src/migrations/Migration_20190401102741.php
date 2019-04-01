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
				`uuid` varchar(40) COLLATE utf8_general_ci NOT NULL,
				`name` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`email` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`password` varchar(255) COLLATE utf8_general_ci NOT NULL,
				`action_token` char(64) COLLATE utf8_general_ci DEFAULT '',
				`access_token` char(64) COLLATE utf8_general_ci DEFAULT '',
				`login_at` datetime DEFAULT NULL,
				`login_ip` varchar(255) DEFAULT '',
				`activated_at` datetime DEFAULT NULL,
				`status` enum('pending','active','banned') COLLATE utf8_general_ci DEFAULT 'pending',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `users_uuid_unique` (`uuid`),
				UNIQUE KEY `users_name_unique` (`name`),
				UNIQUE KEY `users_email_unique` (`email`)
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
