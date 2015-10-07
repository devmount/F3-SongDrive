<?php

namespace Model;

class Author extends \DB\Cortex
{
    protected
        $fieldConf = array(
            'name' => array(
                'type' => \DB\SQL\Schema::DT_VARCHAR256,
                'nullable' => false,
            ),
            'text' => array(
                'has-many' => array('\Model\Song', 'authors_text', 'song_author_text'),
            ),
            'music' => array(
                'has-many' => array('\Model\Song', 'authors_music', 'song_author_music'),
            ),
        ),
        $db = 'DB',
        $table = 'authors',
        $primary = 'id',   // name of the primary key (auto-created), default: id
        $fluid = false,    // triggers the SQL Fluid Mode, default: false
        $ttl = 60,         // caching time of field schema, default: 60
        $rel_ttl = 0;      // caching time of all relations, default: 0
}