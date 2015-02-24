<?php

namespace Anonymizer\Controllers;

use Anonymizer\Templater;

/**
* MainController
*
* Show index page
*
* @author Petrenko P.O. <avallac@academ.org>
*/
class Main extends Controller
{
    public function index()
    {
        $templater = new Templater();
        $templater->Template("views/index");
    }
}