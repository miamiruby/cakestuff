<?php


class AppController extends Controller {
	var $components = array('Auth', 'Cookie');
	var $layout = 'default';

	function beforeFilter() {
		parent::beforeFilter();
		//Set up Auth Component
		$this->Auth->userModel = 'User';
		$this->Auth->autoRedirect = false;
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login', 'admin' => false);
		//$this->Auth->loginAction = array('/login');
		$this->Auth->loginRedirect = '/';
		$this->Auth->logoutRedirect = '/';
		$this->Auth->allow('display');
		//$this->Auth->allow('*');
		if (Configure::read('User.debug')) {
			$this->Auth->allow();
		}
		$this->Auth->authorize = 'controller';
		$this->Auth->userScope = array('User.active' => 1); //user needs to be active.
		$this->set('user_id', $this->Auth->user('id'));

		if (isset($this->params[Configure::read('Routing.admin')])) {
			$this->layout = 'admin';
			$this->set('admin', true);
		} else {
			$this->set('admin', false);
		}
	}	
	function beforeRender() {
	$loggedIn = $this->Cookie->read('Auth.UserLogged');
		if ($this->Auth->user()) {
			if (!isset($loggedIn)) {
				$this->Cookie->write('Auth.UserLogged', 'Logged in');
			}
		}
		elseif (isset($loggedIn)) {
			$this->Cookie->del('Auth.UserLogged');
			$this->Session->setFlash('Session Expired');
		}

		//If we have an authorised user logged then pass over an array of controllers
		//to which they have index action permission
		if($this->Auth->user()){
			$controllerList = Configure::listObjects('controller');
			$permittedControllers = array();
			foreach($controllerList as $controllerItem){
				if($controllerItem <> 'App'){
					if($this->__permitted($controllerItem,'index')){
						$permittedControllers[] = $controllerItem;
					}
				}
			}
		}
		$this->set(compact('permittedControllers'));
	}

	function isAuthorized(){
		return $this->__permitted($this->name,$this->action);
	}

	function __permitted($controllerName,$actionName){
		//Ensure checks are all made lower case
		$controllerName = low($controllerName);
		$actionName = low($actionName);
		//If permissions have not been cached to session...
		if(!$this->Session->check('Permissions')){
			//...then build permissions array and cache it
			$permissions = array();
			//everyone gets permission to logout
			$permissions[]='users:logout';
			
			// var $uses forces weird behavior
			App::import('Model', 'User'); 
			$this->User = new User;

			//Now bring in the current users full record along with groups
			$this->User->contain(array('Group.id'));
			$thisGroups = $this->User->find(array('id'=>$this->Auth->user('id')), 'id');
			$thisGroups = $thisGroups['Group'];
			$this->User->Group->contain(false, array('Permission.name'));
			foreach($thisGroups as $thisGroup){
				$thisPermissions = $this->User->Group->find(array('Group.id'=>$thisGroup['id']), 'id');
				$thisPermissions = $thisPermissions['Permission'];
				foreach($thisPermissions as $thisPermission){
					$permissions[]=low($thisPermission['name']);
				}
			}
			//write the permissions array to session
			$this->Session->write('Permissions',$permissions);
		}else{
			//...they have been cached already, so retrieve them
			$permissions = $this->Session->read('Permissions');
		}
		//Now iterate through permissions for a positive match
		foreach($permissions as $permission){
			if($permission == '*'){
				return true;//Super Admin Bypass Found
			}
			if($permission == $controllerName.':*'){
				return true;//Controller Wide Bypass Found
			}
			if($permission == $controllerName.':'.$actionName){
				return true;//Specific permission found
			}
			/*
			 * @todo Add support for: 'controller/users' - test
			 */
			$permission = low(Router::normalize($permission));
			$url = low(Router::normalize($this->params['url']['url']));
			$url = substr($url, 0, strlen($permission));
			if($permission == $url){
				return true;//Specific permission found
			}
		}
		return false;
	}

}

?>
