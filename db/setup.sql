CREATE TABLE songs (
	id INTEGER,
	created INTEGER,
	title TEXT,
	subtitle TEXT,
	authortext INTEGER,
	authormusic INTEGER,
	year INTEGER,
	tuning TEXT,
	publisher TEXT,
	PRIMARY KEY(time),
	FOREIGN KEY(authortext) REFERENCES authors(id),
	FOREIGN KEY(authormusic) REFERENCES authors(id)
);