<?php

namespace Anonymizer;

/**
* Templater
* 
* Processing templates
* 
* @author Petrenko P.O. <avallac@academ.org>
*/
class Templater
{
    private $content;

    public function addVariant($var, $name)
    {
        $this->$name = $var;
    }
    
    /**
    * Template
    *
    * Compiling template and paste in the layout
    *
    * @param string $TplFile path to template file
    */
    public function template($TplFile)
    {
        ob_start();
        require_once 'protected' . DIRECTORY_SEPARATOR . $TplFile.'.php';
        $this->content = ob_get_contents();
        ob_end_clean();
        require_once 'protected' . DIRECTORY_SEPARATOR . '/views/layout.php';
    }
}