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

		// test
		// $author1 = new \Model\Author();
		// $author1->name = 'Der erste dude';
		// $author1->save();
		// $author2 = new \Model\Author();
		// $author2->name = 'Der zweite dude';
		// $author2->save();

		// $song = new \Model\Song();
		// $song->title = 'Oceans';
		// $song->content = 'You call me out...';
		// $song->authors_text = array($author1, $author2);
		// $song->authors_music = array($author2);
		// $song->save();

		// $song = new \Model\Song();
		// $song->load(array('_id = ?','560e7d01629c12.22187227'));
		// $f3->set('debug', $song->authors_text);

		// set letter list
		$f3->set('letters', range('A', 'Z'));
		// set content template
		$f3->set('content','home.htm');
	}

	/**
	 * Display page to create new song
	 * @param  object $f3 framework
	 * @return void
	 */
	function newSong($f3)
	{
		// set current year as maximum for years list
		$f3->set('currentyear', date('Y'));
		// build author list
		$authors = $f3->get('DB')->read('authors');
		$authorlist = '';
		foreach ($authors as $author) {
			$authorlist .= "'" . $author['name'] . "',";
		}
		$f3->set('authors', '[' . $authorlist . ']');
		// set content template
		$f3->set('content', 'newSong.htm');
	}

	/**
	 * Add new song to db
	 * @param  object $f3 framework
	 * @return void
	 */
	function createSong($f3)
	{
		$song = new \Model\Song();
		$song->copyFrom('POST');
		$song->copyTo('POST');

		// get authors list
		$authors = $f3->get('DB')->read('authors');
		// $f3->set('authors', $authors);

		// redirect to newSong
		$f3->set('content', 'newSong.htm');
	}

	/**
	 */
	function ajaxAuthors($f3)
	{
		// get authors list
		$authors = $f3->get('DB')->read('authors');
		return $authors;
	}

}