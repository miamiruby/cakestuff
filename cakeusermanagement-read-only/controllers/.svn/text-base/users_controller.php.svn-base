<?php
/**
 * User controller
 *
 * User controller
 *
 * @copyright		Copyright 2007-2008, 3HN Deisngs.
 * @author			Kevin Lloyd

 /**
 * Short description for class
 *
 * Long description for class (if any)...
 *
 * @author			Kevin Lloyd
 */
class UsersController extends UserAppController {

	var $name = 'Users';
	var $helpers = array('Html', 'Form', 'Session','Javascript' );
	var $components = array('Auth', 'Cookie', 'Email');
	//var $layout = 'cake';
	var $scaffold;

	var $activationEmail = 'Actication <pop@3hndesigns.com>';
	var $activationSubject = 'Activate Your Account';
	var $forgetEmail = 'Forget Email <pop@3hndesigs.com>';
	var $forgetSubject = 'Forgot Password';
	var $resetEmail = 'Resest Password <pop@3hndesigs.com>';
	var $resetSubject = 'Reset Password';

	function admin_index() {
		$this->User->recursive = -1;
		$this->set('users', $this->paginate());
	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid User.');
			$this->redirect(array('action'=>'index'), null, true);
		}
		$this->set('user', $this->User->read(null, $id));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid User');
			$this->redirect('/admin');
		}
		if (!empty($this->data)) { //  if password blank, unset to avoid validation
			if (empty($this->data['User']['new_password']))	{
				$this->__unsetPassword();
			}
			if ($this->User->save($this->data)) 	{
				$this->Session->setFlash('The User has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else {
				// clear these fields or else they'll be repopulated with hashes. Passwords should be cleared anyway.
				$this->__unsetPassword();
				$this->Session->setFlash('The User could not be saved. Please, try again.');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalid id for User');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if ($this->User->del($id)) {
			$this->Session->setFlash('User #'.$id.' deleted');
			$this->redirect(array('action'=>'index'), null, true);
		}
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data))	{
				$this->Session->setFlash('The User has been saved');
				$this->redirect(array('action'=>'index'), null, true);
			} else { // clear these fields or else they'll be repopulated with hashes. Passwords should be cleared anyway.
				$this->__unsetPassword();
				$this->Session->setFlash('The User could not be saved. Please, try again.');
			}
		}
		$groups = $this->User->Group->find('list', array('conditions' => array('id <>' => 0) ));
		$this->set(compact('groups'));
	}

	/**
	 * Unsets the password fields to avoid validation and avoid blank passwords being processed.
	 * Also used when validation failed to clear old passwords.
	 *
	 * @access private
	 */
	function __unsetPassword() {
		unset($this->data['User']['new_password']);
		unset($this->data['User']['confirm_password']);
		unset($this->data['User']['password']);  // No blank passwords allowed
	}

	function admin_profile() {
		$id = $this->Auth->user('id');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash('Invalid User');
			$this->redirect(array('action'=>'index'), null, true);
		}
		if (!empty($this->data)) {
			$this->data['User']['id'] = $id;

			//  If user leaves blank, don't
			if (empty($this->data['User']['new_password']))	{
				$this->__unsetPassword();
			}
			if ($this->User->save($this->data, true, array('username', 'fname', 'lname', 'email', 'password'))) 	{
				$this->Session->setFlash('Your profile has been saved');
				$this->redirect(array('controller' => 'contents', 'action'=>'index'), null, true);
			} else { // clear these fields or else they'll be repopulated with hashes. Passwords should be cleared anyway.
				$this->__unsetPassword();
				$this->Session->setFlash('The User could not be saved. Please, try again.');
			}
		}

		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
	}

	/**
	 * Confirms user login and writes Remember Me cookie if required.
	 *
	 * Uses CakePHP's included Auth component. Sets a  Remember Me cookie with user_id while login.
	 * If no fields entered (on load) check for presence of cookie and attempt login, deleting cookie where necessary.
	 */
	function login() {
		if ($this->Auth->user()) {
			if (!empty($this->data) && $this->data['User']['remember_me']) {
				$cookie = $this->Auth->user('id');
				$this->Cookie->write('Auth.User', $cookie, true, '+1 day');
				unset($this->data['User']['remember_me']);
			}
			$this->redirect($this->Auth->redirect());
		}
		if (empty($this->data)) {
			$cookie = $this->Cookie->read('Auth.User');
			if (!is_null($cookie)) {
				if ($this->Auth->login($cookie)) {
					//  Clear auth message, just in case we use it.
					$this->Session->del('Message.auth');
					$this->redirect($this->Auth->redirect());
				} else {//  Invalid cookie
					$this->Cookie->del('Auth.User');
				}
			}
		}
	}

	function logout(){
		$this->Session->setFlash('Good-Bye');
		$this->Cookie->del('Auth.User');
		$this->Cookie->del('Auth.UserLogged');
		$this->Session->del('Permissions');
		$this->redirect($this->Auth->logout());
	}

	/**
	 * Adds custom Auth->allow() and set's password = new_password for hashing through Auth
	 *
	 */
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('register', 'confirm', 'forget', 'activate', 'reset', 'admin_profile');
		//  Hash password for data storage
		if (isset($this->data['User']['new_password'])) {
			$this->data['User']['password'] = $this->data['User']['new_password'];
		}
		if ((Configure::read('User.debug')) > 0) {
			$this->Auth->allow();
		}
	}

	/**
	 * Register a new user
	 *
	 * Sends an email to the user with a confirmation link to activate the account. Modifiy email element for Admin authorization
	 *
	 */
	function register() {
		if (!empty($this->data)) {
			$this->User->create();
			if ($this->User->save($this->data, true, array('username', 'fname', 'lname', 'email', 'password'))) {
				extract($this->data['User']);
				$activation = Security::hash($username.$email.$this->User->id);
				$this->User->saveField('activation_code', $activation);
				$this->set('activation_code', $activation);

				if ($this->_sendMailTemplate($email, $this->activationEmail, $this->activationSubject, 'register')) {
					$this->Session->setFlash('Please check your email for the activation link.');
				} else {
					$this->Session->setFlash('Error sending email');
				}
				$this->redirect('/');
			} else { // clear these fields or else they'll be repopulated with hashes. Passwords should be cleared anyway.
				$this->__unsetPassword();
				$this->Session->setFlash('The User could not be registered. Please, try again.');
			}
		}
	}

	/**
	 * Confirm a user's account via an activation link
	 *
	 * User is confirmed with a hash of Security::hash($username.$email.$this->User->id) stored in [activation_code]
	 *
	 */
	function activate($activation) {
		$this->recursive = -1;
		$user = $this->User->find(array('activation_code' => $activation), 'id');
		$this->User->id = $user['User']['id'];
		$user['User']['activation_code'] = null;
		$user['User']['active'] = 1;
		if (!empty($user) && $this->User->save($user, false, array('activation_code', 'active'))) {
			$this->Session->setFlash('User activated. Please login');
		} else {
			$this->Session->setFlash('User NOT activated');
		}
		$this->redirect('/');
	}

	/**
	 * Used if a user forgets their password.
	 *
	 * Generates a new random password for the user and sends them an email to the associated email address. User can search by either
	 * a username or the email address
	 */
	/**
	 * Forget password recovery
	 *
	 * Sends a reset password request to the user. They must receive this and click it to reset the passwords.
	 * Passwords are not reset on a whim. Hash stored in [password_reset]
	 * User enters username OR email address (should be unique)
	 *
	 */
	function forget() {
		if (!empty($this->data)) {
			$username = $this->data['User']['username'];
			$this->recursive = -1;

			//if ($user = $this->User->find("username = '$username' OR email = '$username'", 'fields' => 'id, email')))
			if ($user = $this->User->find(array('or' => array('username' => $username, 'email' => $username)), 'id, username, password, email'))
			{
				extract($user['User']);
				$reset_code = Security::hash($username.$id.$password);
				$this->set('reset_code', $reset_code);
				$this->User->id = $id;
				if ($this->User->savefield('password_reset', $reset_code)  &&
				$this->_sendMailTemplate($email, $this->forgetEmail, $this->forgetSubject, 'forget')) {
					$this->Session->setFlash('A password reset request has been sent. Check you email address: '.$email);
				} else {
					$this->Session->setFlash('Error resetting password. Contact Administrator');
				}
				$this->redirect('/');
			} else {
				$this->Session->setFlash('User does not exist.');
			}
		}
	}

	/**
	 * Reset user password
	 *
	 * Generates a new random password for the user and sends them an email to the associated email address.
	 * Uses [password_reset] from the forget function.
	 */
	function reset($hash) {
		$this->recursive = -1;
		$user = $this->User->find(array('password_reset' => $hash), 'username, id, email');
		$this->User->id = $user['User']['id'];
		if (!empty($user)) {
			extract($user['User']);
			$this->User->id = $id;
			$password = $this->_randomPassword();
			$user['User']['password'] = $this->Auth->password($password);
			$user['User']['password_reset'] = '';
			$this->set(compact('username', 'password'));
			if ($this->User->save($user, false, array('password', 'password_reset')) &&
			$this->_sendMailTemplate($email, $this->resetEmail, $this->resetSubject, 'reset')) {
				$this->Session->setFlash('Your password has been successully reset. Check you email address: '.$email);
			} else {
				$this->Session->setFlash('Error resetting password. Contact Administrator');
			}
			$this->redirect('/');
		} else {
			$this->Session->setFlash('Incorrect reset code');
		}
		$this->redirect('/');
	}

	/**
	 * Produces a random password
	 *
	 * @param int Length of password to generate
	 * return string Random password of supplied length
	 */
	function _randomPassword ($length = 7) {
		$salt = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";  // salt to select chars from
		srand((double)microtime()*1000000); // start the random generator
		$password=""; // set the inital variable
		for ($i=0;$i<$length;$i++) {  // loop and create password
			$password = $password . substr ($salt, rand() % strlen($salt), 1);
		}
		return $password;
	}
	/**
	 *  Send templated email
	 */
	function _sendMailTemplate($to, $from, $subject, $template) {
		$this->Email->to = $to;
		$this->Email->subject = $subject;
		$this->Email->from = $from;
		$this->Email->template = $template;
		return $this->Email->send();
	}
}
?>
