<?php

namespace Anonymizer\Controllers;

use Anonymizer\Templater;

/**
* ErrorController
*
* Show errors pages
*
* @author Petrenko P.O. <avallac@academ.org>
*/
class Error extends Controller
{
    public function action404()
    {
        $templater = new Templater();
        $templater->template("views/error404");
    }
    public function action403()
    {
        $templater = new Templater();
        $templater->template("views/error403");
    }
}
