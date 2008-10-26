<?php
class Permission extends UserAppModel {
	var $name = 'Permission';
	var $displayField = 'description';
	var $hasAndBelongsToMany = array(
            'Group' => array('className' => 'User.Group',
                        'joinTable' => 'groups_permissions',
                        'foreignKey' => 'permission_id',
                        'associationForeignKey' => 'group_id',
                        'unique' => true
		)
	);
	var $actsAs = array('Containable');
}
?>