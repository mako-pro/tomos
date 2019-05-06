<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190505233855 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_locations table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_locations` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`country_id` char(2) NOT NULL DEFAULT 'us',
				`city` varchar(64) DEFAULT '',
				`geo_lat` varchar(20) DEFAULT '',
				`geo_lon` varchar(20) DEFAULT '',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `user_id` (`user_id`) USING BTREE,
				KEY `country_id` (`country_id`),
				CONSTRAINT `tomos_locations_ibfk_1`
					FOREIGN KEY (`user_id`)
					REFERENCES `users` (`id`)
					ON DELETE CASCADE
					ON UPDATE NO ACTION,
				CONSTRAINT `tomos_locations_ibfk_2`
					FOREIGN KEY (`country_id`)
					REFERENCES `tomos_countries` (`id`)
					ON DELETE NO ACTION
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
			"DROP TABLE IF EXISTS `tomos_locations`;"
		);
	}
}
