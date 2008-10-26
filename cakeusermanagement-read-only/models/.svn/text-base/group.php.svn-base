<?php
class Group extends UserAppModel {

	var $name = 'Group';
	var $validate = array(
		'name' => array('Name is Required' => VALID_NOT_EMPTY),
	);
	var $actsAs = array('Sluggable' => array('label' => 'name', 'overwrite' => true), 'Containable'); 

	var $hasAndBelongsToMany = array(
            'Permission' => array('className' => 'User.Permission',
                        'joinTable' => 'groups_permissions',
                        'foreignKey' => 'group_id',
                        'associationForeignKey' => 'permission_id',
                        'unique' => true
		),
            'User' => array('className' => 'User.User',
                        'joinTable' => 'groups_users',
                        'foreignKey' => 'group_id',
                        'associationForeignKey' => 'user_id',
                        'unique' => true
		)
	);
}
?>