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
 * @package    Zend_Paginator
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DbSelectTest.php 11763 2008-10-09 01:39:04Z mratzloff $
 */

/**
 * @see Zend_Paginator_Adapter_DbSelect
 */
require_once 'Zend/Paginator/Adapter/DbSelect.php';

/**
 * @see Zend_Db_Adapter_Pdo_Sqlite
 */
require_once 'Zend/Db/Adapter/Pdo/Sqlite.php';

/**
 * @see PHPUnit_Framework_TestCase
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__) . '/../_files/TestTable.php';

/**
 * @category   Zend
 * @package    Zend_Paginator
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Paginator_Adapter_DbSelectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Zend_Paginator_Adapter_DbSelect
     */
    private $_adapter;
    
    /**
     * @var Zend_Db_Adapter_Pdo_Sqlite
     */
    private $_db;
    
    /**
     * @var Zend_Db_Select
     */
    private $_query;
    
    /**
     * @var Zend_Db_Table_Abstract
     */
    protected $_table;
    
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        
        $this->_db = new Zend_Db_Adapter_Pdo_Sqlite(array(
            'dbname' => dirname(__FILE__) . '/../_files/test.sqlite'
        ));
        
        $this->_table = new TestTable($this->_db);
        
        $this->_query = $this->_db->select()->from('test')
                                            ->order('number ASC') // ZF-3740
                                            ->limit(1000, 0); // ZF-3727
        
        $this->_adapter = new Zend_Paginator_Adapter_DbSelect($this->_query);
    }
    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->_adapter = null;
        parent::tearDown();
    }
    
    public function testGetsItemsAtOffsetZero()
    {
        $actual = $this->_adapter->getItems(0, 10);
        
        $i = 1;
        foreach ($actual as $item) {
        	$this->assertEquals($i, $item['number']);
        	$i++;
        }
    }
    
    public function testGetsItemsAtOffsetTen()
    {
        $actual = $this->_adapter->getItems(10, 10);
        
        $i = 11;
        foreach ($actual as $item) {
            $this->assertEquals($i, $item['number']);
            $i++;
        }
    }
    
    public function testAcceptsIntegerValueForRowCount()
    {
        $this->_adapter->setRowCount(101);
        $this->assertEquals(101, $this->_adapter->count());
    }
    
    public function testThrowsExceptionIfInvalidQuerySuppliedForRowCount()
    {
        try {
            $this->_adapter->setRowCount($this->_db->select()->from('test'));
        } catch (Exception $e) {
            $this->assertType('Zend_Paginator_Exception', $e);
            $this->assertContains('Row count column not found', $e->getMessage());
        }

        try {
            $expr = new Zend_Db_Expr('COUNT(*) AS wrongcolumn');
            $query = $this->_db->select($expr)->from('test');
            
            $this->_adapter->setRowCount($query);
        } catch (Exception $e) {
            $this->assertType('Zend_Paginator_Exception', $e);
            $this->assertEquals('Row count column not found', $e->getMessage());
        }
    }
    
    public function testAcceptsQueryForRowCount()
    {
        $expression = new Zend_Db_Expr('COUNT(*) AS ' . Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN);
        
        $rowCount = clone $this->_query;
        $rowCount->reset(Zend_Db_Select::COLUMNS)
                 ->reset(Zend_Db_Select::ORDER) // ZF-3740
                 ->reset(Zend_Db_Select::LIMIT_OFFSET) // ZF-3727
                 ->reset(Zend_Db_Select::GROUP) // ZF-4001
                 ->columns($expression);
        
        $this->_adapter->setRowCount($rowCount);
                                          
        $this->assertEquals(500, $this->_adapter->count());
    }
    
    public function testThrowsExceptionIfInvalidRowCountValueSupplied()
    {
        try {
            $this->_adapter->setRowCount('invalid');
        } catch (Exception $e) {
            $this->assertType('Zend_Paginator_Exception', $e);
            $this->assertEquals('Invalid row count', $e->getMessage());
        }
    }
    
    public function testReturnsCorrectCountWithAutogeneratedQuery()
    {
        $expected = 500;
        $actual = $this->_adapter->count();
        
        $this->assertEquals($expected, $actual);
    }
    
    public function testDbTableSelectDoesNotThrowException()
    {
        $adapter = new Zend_Paginator_Adapter_DbSelect($this->_table->select());
        $count = $adapter->count();
        $this->assertEquals(500, $count);
    }
    
    /**
     * @group ZF-4001
     */
    public function testGroupByQueryReturnsOneRow()
    {
        $query = $this->_db->select()->from('test')
                           ->order('number ASC')
                           ->limit(1000, 0)
                           ->group('number');
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(500, $adapter->count());
    }
    
    /**
     * @group ZF-4001
     */
    public function testGroupByQueryOnEmptyTableReturnsRowCountZero()
    {
        $db = new Zend_Db_Adapter_Pdo_Sqlite(array(
            'dbname' => dirname(__FILE__) . '/../_files/testempty.sqlite'
        ));
        
        $query = $db->select()->from('test')
                              ->order('number ASC')
                              ->limit(1000, 0);
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(0, $adapter->count());
    }
    
    /**
     * @group ZF-4001
     */
    public function testGroupByQueryReturnsCorrectResult()
    {
        $query = $this->_db->select()->from('test')
                                     ->order('number ASC')
                                     ->limit(1000, 0)
                                     ->group('testgroup');
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(2, $adapter->count());
    }
    
    /**
     * @group ZF-4032
     */
    public function testDistinctColumnQueryReturnsCorrectResult()
    {
        $query = $this->_db->select()->from('test', 'testgroup')
                                     ->order('number ASC')
                                     ->limit(1000, 0)
                                     ->distinct();
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(2, $adapter->count());
    }
    
    /**
     * @group ZF-4094
     */
    public function testSelectSpecificColumns()
    {
        $query = $this->_db->select()->from('test', array('testgroup', 'number'))
                                     ->where('number >= ?', '1');
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(500, $adapter->count());                       
    }
    
    /**
     * @group ZF-4177
     */
    public function testSelectDistinctAllUsesRegularCountAll()
    {
        $query = $this->_db->select()->from('test')
                                     ->distinct();
        $adapter = new Zend_Paginator_Adapter_DbSelect($query);
        
        $this->assertEquals(500, $adapter->count());  
    }
}
