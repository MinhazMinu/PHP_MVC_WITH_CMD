<?php

namespace Controller;

defined('ROOTPATH') or exit('Access Denied!');

/**
 * signup class
 */
class Signup extends MainController
{

	public function index()
	{

		$data['user'] = new \Model\User;
		$req = new \Core\Request;
		if ($req->posted()) {
			$data['user']->signup($_POST);
		}

		$this->view('signup', $data);
	}
}
