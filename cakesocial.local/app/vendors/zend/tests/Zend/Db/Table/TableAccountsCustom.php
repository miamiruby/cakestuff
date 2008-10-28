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
 * @version    $Id: TableAccountsCustom.php 4631 2007-04-30 21:12:37Z darby $
 */


/**
 * @see Zend_Db_Table_TableAccounts
 */
require_once 'Zend/Db/Table/TableAccounts.php';


/**
 * @see Zend_Db_Table_Row_TestMyRow
 */
require_once 'Zend/Db/Table/Row/TestMyRow.php';


/**
 * @see Zend_Db_Table_Row_TestMyRowset
 */
require_once 'Zend/Db/Table/Rowset/TestMyRowset.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Table_TableAccountsCustom extends Zend_Db_Table_TableAccounts
{
    protected $_rowClass    = 'Zend_Db_Table_Row_TestMyRow';
    protected $_rowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';

    protected $_dependentTables = array('Zend_Db_Table_TableBugsCustom');
}
