<?php

namespace Anonymizer\Controllers;

use Tests\TestApp;

/**
* TestController
*
* Page with tests for administration
*
* @author Petrenko P.O. <avallac@academ.org>
*/
class Test extends Controller
{
    public function index()
    {
        if ($this->config['forbidden_tests']) {
            $obj = new Error($this->config);
            $obj -> action403();
        } else {
            $templater = new TestApp($this->config);
            $templater->runS1();
            $templater->runS2();
            $templater->ok();
        }
    }
}
