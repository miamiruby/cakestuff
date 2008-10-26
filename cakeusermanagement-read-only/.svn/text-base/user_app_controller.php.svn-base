<?php
/**
 * Main App Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * @copyright		Copyright 2007-2008, 3HN Designs
 * @author			Kevin Lloyd
 */

/**
 * Main App Controller
 *
 * @author	Kevin Lloyd
 */
class UserAppController extends AppController {

	//var $components = array('Auth', 'Cookie');

	/**
	 * Load the Authentication
	 *
	 * @access public
	 */
	function beforeFilter() {
		//Set up Auth Component
		$this->Auth->userModel = 'User';
		$this->Auth->autoRedirect = false;
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false);
		//$this->Auth->loginAction = array('/login');
		$this->Auth->loginRedirect = '/';
		$this->Auth->logoutRedirect = '/';
		$this->Auth->allow('display');
		if (Configure::read('User.debug')) {
			$this->Auth->allow();
		}
		$this->Auth->authorize = 'controller';
		$this->Auth->userScope = array('User.active' => 1); //user needs to be active.
		$this->set('user_id', $this->Auth->user('id'));

		if (isset($this->params[Configure::read('Routing.admin')])) {
			//$this->layout = 'cake';
			$this->set('admin', true);
		} else {
			$this->set('admin', false);
		}
	}
}
?>
