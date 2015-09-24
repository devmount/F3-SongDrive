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
		$f3=Base::instance();
		// Connect to the database
		$db = new \DB\Jig($f3->get('db'), \DB\Jig::FORMAT_JSON);
		// Use database-managed sessions
		new DB\Jig\Session($db);
		// Save frequently used variables
		$this->db = $db;
	}

}
