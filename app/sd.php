<?php

/**
 * SongDrive Processor
 */
class SD extends Controller
{
	// Display our home page
	function home($f3)
	{
		// Retrieve blog entries
		$f3->set('content','home.htm');
	}

}