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
		// set author list
		$f3->set('authors', $this->getAuthors($f3));
		// set languages list
		$f3->set('languages', $this->getLanguages());

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
		// handle authors
		$authors = array(
			'text' => explode(',', $song->authorstext),
			'music' => explode(',', $song->authorsmusic),
		);
		// reset song authors
		$song->clear('authorstext');
		$song->clear('authorsmusic');
		$authors_text = array();
		$authors_music = array();
		// map authors
		$author = new \Model\Author();
		foreach ($authors as $type => $names) {
			foreach ($names as $name) {
				// when name's empty: continue with next
				if (!$name) {
					continue;
				}
				// check if author name matches existing
				$author->load(array('@name = ?', $name));
				if ($author->dry()) {
					// create and insert new author
					$author->name = $name;
					$author->save();
				}
				// add author object to song
				switch ($type) {
					case 'text':
						$authors_text[] = $author->_id;
						break;
					case 'music':
						$authors_music[] = $author->_id;
						break;

					default:
						break;
				}
				// reset mapper for next author
				$author->reset();
			}
		}
		$song->authors_text = $authors_text;
		$song->authors_music = $authors_music;

		// save new song
		$song->save();

		// return message
		$f3->set('message', 'Song saved successfully.');

		// reroute to showSong
		$f3->reroute('@show_song(@id=' . $song->_id . ')');
	}


	/**
	 * Add new song to db
	 * @param  object $f3 framework
	 * @param  array  $params routing parameter
	 * @return void
	 */
	function showSong($f3, $params)
	{
		$song = new \Model\Song();
		$song->load(array('_id = ?', $params['id']));
		$f3->set('song', $song);

		// set content template
		$f3->set('content', 'showSong.htm');
	}


	/**
	 * creates a list of author names for tagit
	 * @param  object $f3 framework
	 * @return string authors list js array
	 */
	private function getAuthors($f3)
	{
		$authors = $f3->get('DB')->read('authors');
		$authorlist = '';
		foreach ($authors as $author) {
			$authorlist .= "'" . $author['name'] . "',";
		}
		return '[' . $authorlist . ']';
	}

	/**
	 * creates a list of languages
	 * @param  object $f3 framework
	 * @return array language short => language label
	 */
	private function getLanguages()
	{
		return array(
			'en-EN' => 'English',
			'de-DE' => 'German',
		);
	}

}