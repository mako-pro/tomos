<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190401120643 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create groups_users table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `groups_users` (
				`group_id` int(11) unsigned NOT NULL,
				`user_id` int(11) unsigned NOT NULL,
				UNIQUE KEY `group_user_unique` (`group_id`,`user_id`),
				KEY `group_id` (`group_id`),
				KEY `user_id` (`user_id`),
				CONSTRAINT `groups`
					FOREIGN KEY (`group_id`)
					REFERENCES `groups` (`id`)
					ON DELETE CASCADE ON UPDATE NO ACTION,
				CONSTRAINT `users`
					FOREIGN KEY (`user_id`)
					REFERENCES `users` (`id`)
					ON DELETE CASCADE ON UPDATE NO ACTION
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
			"DROP TABLE IF EXISTS `groups_users`;"
		);
	}
}
