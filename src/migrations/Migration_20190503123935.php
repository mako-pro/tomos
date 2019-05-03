<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190503123935 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create profiles table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `profiles` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) unsigned NOT NULL,
			  `first_name` varchar(255) NOT NULL DEFAULT '',
			  `last_name` varchar(255) NOT NULL DEFAULT '',
			  `phone` varchar(255) NOT NULL DEFAULT '',
			  `address` varchar(255) NOT NULL DEFAULT '',
			  `country_id` int(11) unsigned DEFAULT NULL,
			  `birthday` date DEFAULT NULL,
			  `avatar` varchar(255) NOT NULL DEFAULT '',
			  `image` varchar(255) NOT NULL DEFAULT '',
			  `last_login` datetime DEFAULT NULL,
			  `last_ip` varchar(255) NOT NULL DEFAULT '',
			  PRIMARY KEY (`id`),
			  KEY `user_id` (`user_id`),
			  CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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
			"DROP TABLE IF EXISTS `profiles`;"
		);
	}
}
