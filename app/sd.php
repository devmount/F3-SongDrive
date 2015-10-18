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
		$f3->set('letters', array_keys($this->getGroupedSongs()));

		// set recent song lists
		$songs = new \Model\Song();
		$created = $songs->find(array('created <> ""'), array('order' => 'created DESC', 'limit' => 5));
		$songs->reset();
		$updated = $songs->find(array('updated <> ""'), array('order' => 'updated DESC', 'limit' => 5));
		$f3->set('recentsongs', array('created' => $created, 'updated' => $updated));

		// set content template
		$f3->set('heading', 'Dashboard');
		$f3->set('footerinfo', 'Dashboard');
		$f3->set('content','home.htm');
	}


	/**
	 * List existing songs
	 * @param  object $f3 framework
	 * @return void
	 */
	function listSongs($f3)
	{
		// get optional parameter to only list songs with given initial
		$i =  $f3->get('PARAMS.initial');
		// get grouped songs
		$songs = $this->getGroupedSongs();

		// set letter ...
		$f3->set('letters', array_keys($songs));
		// ... and list of either all songs or only songs with given initial
		$f3->set('songs', $i ? array($i => $songs[$i]) : $songs);

		// set content template
		$f3->set('heading', 'List songs');
		$f3->set('content', 'listSongs.htm');
	}


	/**
	 * Add new song to db
	 * @param  object $f3 framework
	 * @return void
	 */
	function showSong($f3)
	{
		$song = new \Model\Song();
		$song->load(array('_id = ?', $f3->get('PARAMS.id')));
		$f3->set('song', $song->cast());

		// set content template
		$f3->set('heading', $song->title);
		$f3->set('subheading', $song->subtitle);
		$f3->set('songfooter', TRUE);
		$f3->set('content', 'showSong.htm');
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
		$f3->set('heading', 'Create a new song');
		$f3->set('submit', 'Create');
		$f3->set('content', 'formFields.htm');
	}


	/**
	 * Display page to create new song
	 * @param  object $f3 framework
	 * @return void
	 */
	function editSong($f3)
	{
		// set current year as maximum for years list
		$f3->set('currentyear', date('Y'));
		// set author list
		$f3->set('authors', $this->getAuthors($f3));
		// set languages list
		$f3->set('languages', $this->getLanguages());

		// load song
		$song = new \Model\Song();
		$song->load(array('_id = ?', $f3->get('PARAMS.id')));

		// prefill author lists
		$authors_text = array();
		$authors_music = array();
		if ($song->authors_text) {
			foreach ($song->authors_text as $author) {
				$authors_text[] = $author->name;
			}
		}
		if ($song->authors_music) {
			foreach ($song->authors_music as $author) {
				$authors_music[] = $author->name;
			}
		}
		$f3->set('authors_text', implode(',', $authors_text));
		$f3->set('authors_music', implode(',', $authors_music));

		// copy song object to formula
		$song->copyTo('POST');

		// set content template
		$f3->set('heading', 'Edit a song');
		$f3->set('submit', 'Update');
		$f3->set('content', 'formFields.htm');
	}


	/**
	 * Save song to db
	 * @param  object $f3 framework
	 * @return void
	 */
	function saveSong($f3)
	{
		// initialize song
		$song = new \Model\Song();

		// handle existing song update
		if ($f3->exists('PARAMS.id')) {
			$song->load(array('_id = ?', $f3->get('PARAMS.id')));
			$song->updated = date("Y-m-d H:i:s");
		}

		// get form data
		$song->copyFrom('POST');

		// handle authors
		$authors = array(
			'text' => $song->authors_text,
			'music' => $song->authors_music,
		);
		// reset song authors
		$song->clear('authors_text');
		$song->clear('authors_music');
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

		// set success message
		$f3->set('SESSION.flash',
			array(
				'type' => 'notice',
				'title' => 'Song saved',
				'message' => 'Database updated successfully.',
			)
		);

		// reroute to showSong
		$f3->reroute('@show_song(@id=' . $song->_id . ')');
	}


	/**
	 * Display page to delete a song
	 * @param  object $f3 framework
	 * @return void
	 */
	function removeSong($f3)
	{
		// delete song
		$song = new \Model\Song();
		$song->load(array('_id = ?', $f3->get('PARAMS.id')));
		$song->erase();

		// set success message
		$f3->set('SESSION.flash',
			array(
				'type' => 'notice',
				'title' => 'Song deleted',
				'message' => 'Database updated successfully.',
			)
		);

		// reroute to home
		$f3->reroute('@home');
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

	/**
	 * creates a list of languages
	 * @return array initial => array(songs)
	 */
	private function getGroupedSongs()
	{
		// get letters and init song mapper
		$letters = range('A', 'Z');
		$song = new \Model\Song();
		$songs_grouped = array();

		// group songs by initial
		foreach ($letters as $letter) {
			$result = $song->find(array('@title LIKE "?"', $letter . '%'));
			if ($result) {
				$songs_grouped[$letter] = $result;
			}
			$song->reset();
		}

		return $songs_grouped;
	}

}