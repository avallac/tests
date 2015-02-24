<?php

namespace Anonymizer;

use Anonymizer\Controllers\Error;
use Anonymizer\Models\UrlModel;

/**
* WebApplication
* 
* Route requests between controllers
* 
* @author Petrenko P.O. <avallac@academ.org>
*/
class WebApplication
{
    private $controller;
    private $controllerFunction;

    /**
    * Constructor
    * 
    * Load configuration file
    * 
    * @param string $config path to configuration file
    */
    function __construct($config)
    {
        $this->config = include_once($config);
    }

    /**
    * Main function 
    *
    * Initialize the router and the controller
    */
    public function run()
    {
        $this->router();
        $this->controller();
    }
    
    /**
    * Main controller function 
    *
    * If the controller is not found check the short URL
    * Or display a 404 error
    */
    private function controller()
    {
        $class = "Anonymizer\\Controllers\\".ucfirst($this->controller);
        if (class_exists($class)) {
            $obj = new $class($this->config);
            if (method_exists($obj, $this->controllerFunction)) {
                $obj->load($this->controllerFunction);
            } else {
                $obj = new Error($this->config);
                $obj -> action404();
            }
        } else {
            $record = new UrlModel($this->config);
            $record->loadHash($this->controller);
            if ($record->getUrl()) {
                $templater = new Templater();
                $templater->addVariant($record->getUrl(), 'location');
                $templater->template("views/redirect");
            } else {
                $obj = new Error($this->config);
                $obj -> action404();
            }
        }
    }
    
    /**
    * Router function 
    *
    * Parse the incoming request to determine the called controller and its functions
    * The resulting information is in '_controller' and '_controllerFunction'
    */
    private function router()
    {
        $this->controller='main';
        $this->controllerFunction = 'index';
        $q = trim(preg_replace("~\?.*~", '', $_SERVER['REQUEST_URI']), "/ \n\r\t");
        if ($q) {
            $list = explode('/', $q);
            $controller = trim(array_shift($list), " \t\n\r");
            $controllerFunction = '';
            if ($controller!='') {
                $this->controller = $controller;
            }
            if (isset($list[0])) {
                $controllerFunction = trim(array_shift($list), " \t\n\r");
            }
            if ($controllerFunction!='') {
                $this->controllerFunction = $controllerFunction;
            }
        }
    }
}

