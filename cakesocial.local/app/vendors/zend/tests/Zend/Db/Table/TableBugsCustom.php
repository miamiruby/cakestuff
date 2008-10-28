<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TableBugsCustom.php 5896 2007-07-27 20:04:24Z bkarwin $
 */


/**
 * @see Zend_Db_Table_TableBugs
 */
require_once 'Zend/Db/Table/TableBugs.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Table_TableBugsCustom extends Zend_Db_Table_TableBugs
{
    public $isMetadataFromCache = false;

    protected $_rowClass    = 'Zend_Db_Table_Row_TestMyRow';
    protected $_rowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';

    protected $_dependentTables = array('Zend_Db_Table_TableBugsProductsCustom');

    protected $_referenceMap    = array(
        'Reporter' => array(
            'columns'           => array('reported_by'),
            'refTableClass'     => 'Zend_Db_Table_TableAccountsCustom',
            'refColumns'        => array('account_name'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE
        ),
        'Engineer' => array(
            'columns'           => 'assigned_to',
            'refTableClass'     => 'Zend_Db_Table_TableAccountsCustom',
            'refColumns'        => 'account_name'
        ),
        'Verifier' => array(
            'columns'           => 'verified_by',
            'refTableClass'     => 'Zend_Db_Table_TableAccountsCustom',
            'refColumns'        => 'account_name'
        )
    );

    /**
     * Public proxy to setup functionality
     *
     * @return void
     */
    public function setup()
    {
        $this->_setup();
    }

    /**
     * Turnkey for initialization of a table object.
     *
     * @return void
     */
    protected function _setup()
    {
        $this->_setupDatabaseAdapter();
        $this->_setupTableName();
        $this->isMetadataFromCache = $this->_setupMetadata();
        $this->_setupPrimaryKey();
    }
}
