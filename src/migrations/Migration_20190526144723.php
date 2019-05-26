<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190526144723 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_articles table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_articles` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`parent_id` int(11) unsigned NOT NULL DEFAULT '0',
				`category_id` int(11) unsigned NOT NULL DEFAULT '1',
				`user_id` int(11) unsigned NOT NULL,
				`uuid` char(10) NOT NULL,
				`slug` varchar(255) NOT NULL DEFAULT '',
				`page` int(4) unsigned NOT NULL DEFAULT '1',
				`cover` varchar(60) NOT NULL DEFAULT '',
				`title` varchar(255) NOT NULL DEFAULT '',
				`body` text NOT NULL,
				`enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `uuid` (`uuid`),
				KEY `user_id` (`user_id`),
				CONSTRAINT `tomos_articles_ibfk_1`
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
			"DROP TABLE IF EXISTS `tomos_articles`;"
		);
	}
}
