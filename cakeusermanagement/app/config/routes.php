<?php
/* SVN FILE: $Id: routes.php 7690 2008-10-02 04:56:53Z nate $ */
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.app.config
 * @since			CakePHP(tm) v 0.2.9
 * @version			$Revision: 7690 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-10-02 00:56:53 -0400 (Thu, 02 Oct 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */


	$admin = '/'.Configure::read('Routing.admin');

	// Controller routing.
	
	Router::connect('/users/', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/users/:action', array('controller' => 'users'));
	
	Router::connect($admin.'/users/', array('controller' => 'users', 'action' => 'index', 'admin'=>true));
	Router::connect($admin.'/users/:action', array('controller' => 'users', 'admin'=>true));
	
	// User routes
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/forget', array('controller' => 'users', 'action' => 'forget'));
	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/activate/*', array('controller' => 'users', 'action' => 'activate'));
	Router::connect('/reset/*', array('controller' => 'users', 'action' => 'reset'));
	Router::connect('/profile', array('controller' => 'users', 'action' => 'profile'));
	    


/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
?>
