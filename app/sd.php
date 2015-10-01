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
		// set letter list
		$f3->set('letters', range('A', 'Z'));
		// set content part
		$f3->set('content','home.htm');
	}

}