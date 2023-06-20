<?php

namespace Controller;

use \Core\Session;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * {CLASSNAME} class
 */
class {CLASSNAME} extends MainController
{


	public function index()
	{
		$ses = new Session;
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

		$this->view('{classname}');
	}

}
