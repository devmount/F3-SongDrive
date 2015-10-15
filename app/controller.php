<?php

/**
 * Controller for SongMount models
 */
class Controller
{
	/**
	 * HTTP route pre-processor
	 */
	function beforeroute($f3)
	{

	}

	/**
	 * HTTP route post-processor
	 */
	function afterroute()
	{
		// Render HTML layout
		echo Template::instance()->render('layout.htm');
		// clear flash messages
		\Base::instance()->clear('SESSION.flash');
	}

	/**
	 * Instantiate class
	 */
	function __construct()
	{
		// Initialize framework
		$f3 = \Base::instance();
		// Initialize database
		$f3->set('DB', new \DB\Jig('db/', DB\Jig::FORMAT_JSON));
		// Initialize jig based sessions
		new \DB\Jig\Session($f3->get('DB'));
		// Initialize models
		\Model\Song::setup();
		\Model\Author::setup();
	}

}
