<?php
/**
 * Main App Model
 *
 * @copyright		Copyright 2007-2008, 3HN Designs.
 * @author			Kevin Lloyd
 */

/**
 * Main App Model
 *
 * @author			Kevin Lloyd
 */
class UserAppModel extends AppModel {

	/**
	 * Set up user talbe and table prefix based on core Configurations
	 */
	function __construct($id = false, $table = null, $ds = null) {
		if (defined('CAKE_UNIT_TEST') && CAKE_UNIT_TEST) {
			$this->useDbConfig = 'test';
		} else {
			if ($useDbConfig = Configure::read('User.useDbConfig')) {
				$this->useDbConfig = $useDbConfig;
			}
		}
		parent::__construct($id, $table, $ds);
	}

	/**
	 * Validate whether there already exists this field in the database. straight from TempDocs {@link http://tempdocs.cakephp.org/#TOC133258}
	 *
	 * @param type $data Value of field being fed in by validation rules
	 * @param string $fieldName Field to check for uniqueness
	 * @return boolean True if unique, false if not.
	 * @access public
	 */
	function checkUnique($data, $fieldName) {
		$valid = false;
		if(isset($fieldName) && $this->hasField($fieldName)) {
			$valid = $this->isUnique(array($fieldName => $data));
		}
		return $valid;
	}

	/**
	 * Validate whether one field (or group of fields) is equal to another.
	 *
	 * @param mixed $field Value of field (group of fields) being fed in by validation rules
	 * @param string $compare_field Field to compare to
	 * @return boolen True if identical, false if not
	 * @access public
	 */
	function identicalFieldValues( $field, $compare_field=null ) {
		return $this->data[$this->name][ $compare_field ] == array_shift($field);
	}
}
?>
