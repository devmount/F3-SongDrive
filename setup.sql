PRAGMA foreign_keys = ON;

-- table of song contents
DROP TABLE IF EXISTS songs;
CREATE TABLE songs (
	id INTEGER NOT NULL,
	lang TEXT NOT NULL,
	tstamp INTEGER NOT NULL,
	title TEXT NOT NULL,
	subtitle TEXT,
	-- authortext INTEGER,
	-- authormusic INTEGER,
	year INTEGER,
	tuning TEXT,
	publisher TEXT,
	content TEXT NOT NULL,
	PRIMARY KEY(id, lang)
);

-- table of author names
DROP TABLE IF EXISTS authors;
CREATE TABLE authors (
	id INTEGER NOT NULL,
	name TEXT NOT NULL,
	PRIMARY KEY(id)
);

-- m:m songs:authorstext
DROP TABLE IF EXISTS songs_authorstext;
CREATE TABLE songs_authorstext (
	sid INTEGER NOT NULL,
	slang TEXT NOT NULL,
	aid INTEGER NOT NULL,
	PRIMARY KEY(sid, slang, aid),
	FOREIGN KEY(sid, slang) REFERENCES songs(id, lang),
	FOREIGN KEY(aid) REFERENCES authors(id)
);

-- m:m songs:authorsmusic
DROP TABLE IF EXISTS songs_authorsmusic;
CREATE TABLE songs_authorsmusic (
	sid INTEGER NOT NULL,
	slang TEXT NOT NULL,
	aid INTEGER NOT NULL,
	PRIMARY KEY(sid, slang, aid),
	FOREIGN KEY(sid, slang) REFERENCES songs(id, lang),
	FOREIGN KEY(aid) REFERENCES authors(id)
);