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
		// Initialize framework
		$f3 = \Base::instance();
		// Initialize database
		$f3->set('DB', new \DB\Jig('db/', DB\Jig::FORMAT_JSON));
		// Initialize models
		\Model\Song::setup();
		\Model\Author::setup();
	}

}
