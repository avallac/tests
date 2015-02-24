<?php

namespace Anonymizer\Models;

/**
* Abstract class for all models
* 
* @author Petrenko P.O. <avallac@academ.org>
*/
abstract class Model
{
    protected $db;
    protected $config;

    /**
    * Constructor
    * 
    * Store configuration array
    * 
    * @param array $config configuration array
    */
    function __construct($config)
    {
        $this->config = $config;
        $db = $this->config['db'];
        $this->db = new \PDO($db['dsn'], $db['username'], $db['password']);
    }
}
