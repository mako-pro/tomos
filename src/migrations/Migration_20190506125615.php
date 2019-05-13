<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190506125615 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Create tomos_settings table';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"CREATE TABLE `tomos_settings` (
				`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
				`user_id` int(11) unsigned NOT NULL,
				`can_view` enum('everyone','group-members','my-followers','only-me') DEFAULT 'everyone' COMMENT 'Who can see user profile',
				`can_tag` enum('everyone','group-members','my-followers') DEFAULT 'everyone' COMMENT 'Who can tag user',
				`show_followers` enum('1','0') DEFAULT '0' COMMENT 'Show the followers',
				`show_email` enum('1','0') DEFAULT '0' COMMENT 'Show email address',
				`show_phone` enum('1','0') DEFAULT '0' COMMENT 'Show phone',
				`show_experiences` enum('1','0') DEFAULT '0' COMMENT 'Show experiences',
				`show_educations` enum('1','0') DEFAULT '0' COMMENT 'Show educations',
				`show_age` enum('1','0') DEFAULT '0' COMMENT 'Show user age',
				`allow_follow` enum('1','0') DEFAULT '0' COMMENT 'Allow to follow the user',
				`created_at` datetime NOT NULL,
				`updated_at` datetime NOT NULL,
				PRIMARY KEY (`id`),
				UNIQUE KEY `user_id` (`user_id`) USING BTREE,
				CONSTRAINT `tomos_settings_ibfk_1`
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
			"DROP TABLE IF EXISTS `tomos_settings`;"
		);
	}
}
