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
 * @version    $Id: TableBugsProducts.php 7508 2008-01-19 03:12:38Z peptolab $
 */


/**
 * @see Zend_Db_Table_Abstract
 */
require_once 'Zend/Db/Table/Abstract.php';


PHPUnit_Util_Filter::addFileToFilter(__FILE__);


/**
 * @category   Zend
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Db_Table_TableBugsProducts extends Zend_Db_Table_Abstract
{
    protected $_name    = 'zfbugs_products';

    protected $_referenceMap    = array(
        'Bug' => array(
            'columns'           => 'bug_id', // Deliberate non-array value
            'refTableClass'     => 'Zend_Db_Table_TableBugs',
            'refColumns'        => array('bug_id'),
            'onDelete'          => -1, // Deliberate false value
            'onUpdate'          => -1 // Deliberate false value
        ),
        'Product' => array(
            'columns'           => array('product_id'),
            'refTableClass'     => 'Zend_Db_Table_TableProducts',
            'refColumns'        => array('product_id'),
            'onDelete'          => self::CASCADE,
            'onUpdate'          => self::CASCADE
        )
    );

}
