<?php

/**
 * SongDrive Processor
 */
class SD extends Controller
{
	/**
	 * Display home page
	 * @param  object $f3 framework
	 * @return void
	 */
	function home($f3)
	{
		// Retrieve blog entries
		$f3->set('content','home.htm');
	}

}