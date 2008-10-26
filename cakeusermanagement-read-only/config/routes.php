<?php

	$admin = '/'.Configure::read('Routing.admin');

	// Controller routing.
	
	Router::connect('/users/', array('plugin'=>'user', 'controller' => 'users', 'action' => 'index'));
	Router::connect('/users/:action', array('plugin'=>'user', 'controller' => 'users'));
	Router::connect('/users/:controller/:action', array('plugin'=>'user'));
	
	Router::connect($admin.'/users/', array('plugin'=>'user', 'controller' => 'users', 'action' => 'index', 'admin'=>true));
	Router::connect($admin.'/users/:action', array('plugin'=>'user', 'controller' => 'users', 'admin'=>true));
	
	// User routes
	Router::connect('/login', array('plugin' => 'user', 'controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('plugin' => 'user', 'controller' => 'users', 'action' => 'logout'));
	Router::connect('/forget', array('plugin' => 'user', 'controller' => 'users', 'action' => 'forget'));
	Router::connect('/register', array('plugin' => 'user', 'controller' => 'users', 'action' => 'register'));
	Router::connect('/activate/*', array('plugin' => 'user', 'controller' => 'users', 'action' => 'activate'));
	Router::connect('/reset/*', array('plugin' => 'user', 'controller' => 'users', 'action' => 'reset'));
	Router::connect('/profile', array('plugin' => 'user', 'controller' => 'users', 'action' => 'profile'));
?>