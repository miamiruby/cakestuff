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
 * @version    $Id: TableBugsProductsCustom.php 4778 2007-05-11 18:25:32Z darby $
 */


/**
 * @see Zend_Db_Table_TableBugsProducts
 */
require_once 'Zend/Db/Table/TableBugsProducts.php';


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
class Zend_Db_Table_TableBugsProductsCustom extends Zend_Db_Table_TableBugsProducts
{
    protected $_rowClass    = 'Zend_Db_Table_Row_TestMyRow';
    protected $_rowsetClass = 'Zend_Db_Table_Rowset_TestMyRowset';

    protected $_referenceMap    = array(
        'Bug' => array(
            'columns'           => 'bug_id',
            'refTableClass'     => 'Zend_Db_Table_TableBugsCustom',
            'refColumns'        => 'bug_id',
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE
        ),
        'Product' => array(
            'columns'           => 'product_id',
            'refTableClass'     => 'Zend_Db_Table_TableProductsCustom',
            'refColumns'        => 'product_id',
            'onDelete'          => 'anything but self::CASCADE',
            'onUpdate'          => 'anything but self::CASCADE'
        )
    );
}
