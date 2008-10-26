This authentication will consist of the following:

 - User Model and Controller using the native CakePHP Auth Component using 'controller' authorization.
 - Remember Me Cookie
 - isUnique validation for the username and/or email address.
 - User Confirm validation for the email address and/or passwords.
 
 - todo: Add debug query string to use in combo with Admin.debug to allow everything for setup purposes
 - todo: Configure for different user table/prefix/db_config?
 - todo: Test with diff user tables/prefix
 - todo: Just add more config
 - todo: Move login/logig Redirect or make config
 
 
 
Installation:

- Uncomment Configure::write('Routing.admin', 'admin'); in core.php
- Modify core.php as needed
- If used, add new database config for diff prefix, etc.

- Add these to app_controller in appropriate places:

	var $components = array('Auth', 'Cookie');

	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('display');
		$this->Auth->loginAction = array('plugin'=>'user','controller' => 'users', 'action' => 'login', 'admin' => false);
		$this->set('user_id', $this->Auth->user('id'));
		if () () 
	}
	
	function beforeRender() {
		require(APP.'plugins'.DS.'user'.DS.'app_controller'.DS.'before_render.php');
	}

	function isAuthorized(){
		return require(APP.'plugins'.DS.'user'.DS.'app_controller'.DS.'is_authorized.php');;
	}

	function __permitted($controllerName,$actionName){
		return require(APP.'plugins'.DS.'user'.DS.'app_controller'.DS.'__permitted.php');;
	}
	
	
- Add these to routes.php:
	include(APP.'plugins'.DS.'user'.DS.'config'.DS.'routes.php');

- Add these to core.php:
	include(APP.'plugins'.DS.'user'.DS.'config'.DS.'core.php');
