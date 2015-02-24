<?php

namespace Anonymizer\Controllers;

use Anonymizer\Models\UrlModel;

/**
* AjaxController
*
* Processing Ajax requests
*
* @author Petrenko P.O. <avallac@academ.org>
*/
class Ajax extends Controller
{
    /**
    * ajax/add
    *
    * Saving user's url and return short links... or error
    */
    public function add()
    {
        try {
            $record = new UrlModel($this->config);
        } catch (\PDOException $e) {
            if ($this->config['messages']['try_again']) {
                print $this->config['messages']['try_again'];
            } else {
                print 'Please try again later.';
            }
            die();
        }

        if (!empty($_POST['url'])) {
            if ($hash = $record->saveUrl($_POST['url'])) {
                print $this->config['base_url'] . '/' . $hash;

            } else {
                print $this->config['messages']['cant_create'];
            }
        } else {
            print $this->config['messages']['query_empty'];
        }
    }
}
