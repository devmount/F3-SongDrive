<?php

/**
 * Controller for SongMount models
 */
class Controller
{
	/**
	 * $db Jig database object
	 * @var object
	 */
	protected $db;

	/**
	 * HTTP route pre-processor
	 */
	function beforeroute($f3)
	{
		// initialize db
		$db = $this->db;
		// stats
		$f3->set(
			'stats',
			$f3->format(
				'Page rendered in {0} msecs / Memory usage {1} Kibytes',
				round(microtime(TRUE)-$TIME, 2),
				round(memory_get_usage(TRUE)/1e3, 1)
			)
		);
	}

	/**
	 * HTTP route post-processor
	 */
	function afterroute()
	{
		// Render HTML layout
		echo Template::instance()->render('layout.htm');
	}

	/**
	 * Instantiate class
	 */
	function __construct()
	{
		$f3 = Base::instance();
		// Connect to the database
		$db = new \DB\SQL('sqlite:' . $f3->get('db'));
		if (file_exists($f3->get('dbsetup'))) {
			// Initialize database with default setup
			$db->exec(explode(';', $f3->read(SETUP)));
			// Make default setup inaccessible
			rename($f3->get('dbsetup'), 'db/setup.$ql');
		}
		// Use database-managed sessions
		new DB\SQL\Session($db);
		// Save frequently used variables
		$this->db=$db;
	}

}
