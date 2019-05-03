<?php

namespace placer\tomos\migrations;

use mako\database\migrations\Migration;

class Migration_20190503134013 extends Migration
{
	/**
	 * Description.
	 *
	 * @var string
	 */
	protected $description = 'Add foreign key profiles_ibfk_2';

	/**
	 * Makes changes to the database structure.
	 */
	public function up(): void
	{
		$this->getConnection()->query
		(
			"ALTER TABLE `profiles` ADD
				CONSTRAINT `profiles_ibfk_2`
				FOREIGN KEY (`country_id`)
				REFERENCES `countries` (`id`)
				ON DELETE NO ACTION
				ON UPDATE NO ACTION;"
		);
	}

	/**
	 * Reverts the database changes.
	 */
	public function down(): void
	{
		$this->getConnection()->query
		(
			"ALTER TABLE `profiles` DROP FOREIGN KEY `profiles_ibfk_2`;"
		);
	}
}
