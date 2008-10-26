<?
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
?>