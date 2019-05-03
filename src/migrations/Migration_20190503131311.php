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
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `capital` varchar(255) DEFAULT NULL,
			  `citizenship` varchar(255) DEFAULT NULL,
			  `country_code` varchar(3) NOT NULL DEFAULT '',
			  `currency` varchar(255) DEFAULT NULL,
			  `currency_code` varchar(255) DEFAULT NULL,
			  `currency_sub_unit` varchar(255) DEFAULT NULL,
			  `currency_symbol` varchar(3) DEFAULT NULL,
			  `full_name` varchar(255) DEFAULT NULL,
			  `iso_3166_2` varchar(2) NOT NULL DEFAULT '',
			  `iso_3166_3` varchar(3) NOT NULL DEFAULT '',
			  `name` varchar(255) NOT NULL DEFAULT '',
			  `region_code` varchar(3) NOT NULL DEFAULT '',
			  `sub_region_code` varchar(3) NOT NULL DEFAULT '',
			  `eea` tinyint(1) NOT NULL DEFAULT '0',
			  `calling_code` varchar(3) DEFAULT NULL,
			  `flag` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
