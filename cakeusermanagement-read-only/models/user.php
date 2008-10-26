<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
 *
 * @copyright		Copyright 2007-2008, 3HN Designs.
 */

/**
 * User Model.
 *
 * Contains most of the Authentication. Login, Logout, Validation ech
 *
 */
class User extends UserAppModel {
	var $displayField = 'username';
	
	var $name = 'User';
	var $hasAndBelongsToMany = array('Group' => array('className' => 'User.Group',
                        'joinTable' => 'groups_users',
                        'foreignKey' => 'user_id',
                        'associationForeignKey' => 'group_id',
                        'unique' => true
		)
	);
	var $actsAs = array(
		'Sluggable' => array('label' => array('fname','lname'), 'overwrite' => true),
		'Containable');

/**
 * Validation for user Model
 *
 * Key validation: password, new_password is not empty. Contains rules for confirm_password and confirm_email => identicalFieldValues validation
 *
 * @var array
 * @access private
 */
	var $validate = array(
		'username' => array(
			array('required' => true, 'allowEmpty' => false, 'rule' => 'alphaNumeric'),
            array(
                'rule' => array('checkUnique', 'username'),
                'message' => 'This username is already in use'
            ),
        ),
		'new_password' => array(
			'rule' => array('minLength', 5),
			'message' => 'Password must be 5 characters long',
			//'required' => true, 'allowEmpty' => false,
			),
		'confirm_password' => array(
			'rule' => array('identicalFieldValues', 'new_password' ),
			'message' => 'Please re-enter your password twice so that the values match'
            ),
		'fname' => array('rule' => array('minLength', 1), 'required' => true),
		'lname' => array('rule' => array('minLength', 1), 'required' => true),
		'email' => array(
            array(
			'rule' => array('email', false), 'required' => true,
			'message' =>'Please enter a valid email address'
			),
			array(
			'rule' => array('checkUnique', 'email'),
			'message' => 'This email address is already in use'
			),
		),
		'confirm_email' => array(
			'rule' => array('identicalFieldValues', 'email' ),
			'message' => 'Please re-enter your email address twice so that the values match'
            ),
	);

/**
 * Make email and username lowercase. Ensures they are caseinsensitive
 *
 * @return true
 */
	function beforeValidate(){
		if (isset($this->data['User']['email'])) {
			$this->data['User']['email'] = strtolower($this->data['User']['email']);
		}
		if (isset($this->data['User']['confirm_email'])) {
			$this->data['User']['confirm_email'] = strtolower($this->data['User']['confirm_email']);
		}
		if (isset($this->data['User']['username'])) {
			$this->data['User']['username'] = strtolower($this->data['User']['username']);
		}
	    return true;
	}
}
?>