<?php

namespace Model;

class Song extends \DB\Cortex
{
    protected
        $fieldConf = array(
            'timestamp' => array(
                'type' => \DB\SQL\Schema::DT_TIMESTAMP,
                'nullable' => false,
                'default' => \DB\SQL\Schema::DF_CURRENT_TIMESTAMP,
            ),
            'lang' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR128,
                'nullable' => false,
            ),
            'title' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR256,
                'nullable' => false,
            ),
            'subtitle' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR256,
            ),
            'authors_text' => array(
                'has-many' => array('\Model\Author', 'text', 'song_author_text'),
            ),
            'authors_music' => array(
                'has-many' => array('\Model\Author', 'music', 'song_author_music'),
            ),
            'year' => array(
                'type' => \DB\SQL\Schema::DT_INT,
            ),
            'tuning' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR128,
            ),
            'publisher' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR256,
            ),
            'content' => array(
                'type' => \DB\SQL\Schema::DT_TEXT,
                'nullable' => false,
            ),
        ),
        $db = 'DB',
        $table = 'songs',
        $primary = 'id',   // name of the primary key (auto-created), default: id
        $fluid = false,    // triggers the SQL Fluid Mode, default: false
        $ttl = 60,         // caching time of field schema, default: 60
        $rel_ttl = 0;      // caching time of all relations, default: 0
}