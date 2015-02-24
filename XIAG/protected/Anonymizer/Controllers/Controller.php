<?php

namespace Anonymizer\Controllers;

/**
* Abstract class for all controllers
* 
* @author Petrenko P.O. <avallac@academ.org>
*/
abstract class Controller
{
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
    }

    public function load($fn)
    {
        $this->$fn();
    }
}
