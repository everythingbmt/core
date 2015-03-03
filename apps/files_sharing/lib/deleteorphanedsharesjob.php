<?php
/**
 * Copyright (c) 2015 Vincent Petry <pvince81@owncloud.com>
 * This file is licensed under the Affero General Public License version 3 or
 * later.
 * See the COPYING-README file.
 */

namespace OCA\Files_sharing\Lib;

use OCP\IDBConnection;
use OC\BackgroundJob\TimedJob;

/**
 * Delete all share entries that have no matching entries in the file cache table.
 */
class DeleteOrphanedSharesJob extends TimedJob {

	/** @var int $defaultIntervalMin default interval in minutes */
	protected $defaultIntervalMin = 15;

	/**
	 * makes the background job do its work
	 * @param array $argument
	 */
	public function run($argument) {
		$connection = \OC::$server->getDatabaseConnection();
		// TODO: SQLite...
		$connection->executeUpdate(
			'DELETE `s` FROM `*PREFIX*share` `s` ' .
			'LEFT JOIN `*PREFIX*filecache` `f` ON `s`.`file_source`=`f`.`fileid` ' .
			'WHERE `f`.`fileid` IS NULL;'
		);
	}

}
