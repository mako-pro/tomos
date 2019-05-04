<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190503131311 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create countries table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `countries` (
				`id` char(2) NOT NULL,
				`name` varchar(255) NOT NULL,
				`official_name` varchar(255) NOT NULL DEFAULT '',
				`native_name` varchar(255) NOT NULL DEFAULT '',
				`native_official_name` varchar(255) NOT NULL DEFAULT '',
				`iso_3166_1_alpha2` char(2) NOT NULL,
				`iso_3166_1_alpha3` char(3) NOT NULL,
				`calling_code` int(10) unsigned DEFAULT NULL,
				PRIMARY KEY (`id`)
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
			"DROP TABLE IF EXISTS `countries`;"
		);
	}
}
