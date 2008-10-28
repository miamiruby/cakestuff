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
 */

/**
 * @see Zend_Db_Adapter_TestCommon
 */
require_once 'Zend/Db/Adapter/TestCommon.php';

/**
 * @see Zend_Db_Adapter_Oracle
 */
require_once 'Zend/Db/Adapter/Oracle.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__);

class Zend_Db_Adapter_OracleTest extends Zend_Db_Adapter_TestCommon
{

    protected $_numericDataTypes = array(
        Zend_Db::INT_TYPE    => Zend_Db::INT_TYPE,
        Zend_Db::BIGINT_TYPE => Zend_Db::BIGINT_TYPE,
        Zend_Db::FLOAT_TYPE  => Zend_Db::FLOAT_TYPE,
        'BINARY_DOUBLE'      => Zend_Db::FLOAT_TYPE,
        'BINARY_FLOAT'       => Zend_Db::FLOAT_TYPE,
        'NUMBER'             => Zend_Db::FLOAT_TYPE,
    );

    public function testAdapterDescribeTablePrimaryAuto()
    {
        $this->markTestSkipped('Oracle does not support auto-increment');
    }

    public function testAdapterDescribeTablePrimaryKeyColumn()
    {
        $desc = $this->_db->describeTable('zfproducts');

        $this->assertEquals('zfproducts',        $desc['product_id']['TABLE_NAME']);
        $this->assertEquals('product_id',        $desc['product_id']['COLUMN_NAME']);
        $this->assertEquals(1,                   $desc['product_id']['COLUMN_POSITION']);
        $this->assertEquals('',                  $desc['product_id']['DEFAULT']);
        $this->assertFalse(                      $desc['product_id']['NULLABLE']);
        $this->assertEquals(0,                   $desc['product_id']['SCALE']);
        // Oracle reports precsion 11 for integers
        $this->assertEquals(11,                  $desc['product_id']['PRECISION']);
        $this->assertTrue(                       $desc['product_id']['PRIMARY'], 'Expected product_id to be a primary key');
        $this->assertEquals(1,                   $desc['product_id']['PRIMARY_POSITION']);
        $this->assertFalse(                      $desc['product_id']['IDENTITY']);
    }

    /**
     * Test the Adapter's fetchAll() method.
     */
    public function testAdapterFetchAll()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchAll("SELECT * FROM $products WHERE $product_id > :id ORDER BY $product_id ASC", array(":id"=>1));
        $this->assertEquals(2, count($result));
        $this->assertThat($result[0], $this->arrayHasKey('product_id'));
        $this->assertEquals('2', $result[0]['product_id']);
    }

    /**
     * Test the Adapter's fetchAssoc() method.
     */
    public function testAdapterFetchAssoc()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchAssoc("SELECT * FROM $products WHERE $product_id > :id ORDER BY $product_id DESC", array(":id"=>1));
        foreach ($result as $idKey => $row) {
            $this->assertThat($row, $this->arrayHasKey('product_id'));
            $this->assertEquals($idKey, $row['product_id']);
        }
    }

    /**
     * Test the Adapter's fetchCol() method.
     */
    public function testAdapterFetchCol()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchCol("SELECT * FROM $products WHERE $product_id > :id ORDER BY $product_id ASC", array(":id"=>1));
        $this->assertEquals(2, count($result)); // count rows
        $this->assertEquals(2, $result[0]);
        $this->assertEquals(3, $result[1]);
    }

    /**
     * Test the Adapter's fetchOne() method.
     */
    public function testAdapterFetchOne()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');
        $product_name = $this->_db->quoteIdentifier('product_name');

        $prod = 'Linux';
        $result = $this->_db->fetchOne("SELECT $product_name FROM $products WHERE $product_id > :id ORDER BY $product_id", array(":id"=>1));
        $this->assertEquals($prod, $result);
    }

    /**
     * Test the Adapter's fetchPairs() method.
     */
    public function testAdapterFetchPairs()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');
        $product_name = $this->_db->quoteIdentifier('product_name');

        $prod = 'Linux';
        $result = $this->_db->fetchPairs("SELECT $product_id, $product_name FROM $products WHERE $product_id > :id ORDER BY $product_id ASC", array(":id"=>1));
        $this->assertEquals(2, count($result)); // count rows
        $this->assertEquals($prod, $result[2]);
    }

    /**
     * Test the Adapter's fetchRow() method.
     */
    public function testAdapterFetchRow()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');
        $product_id = $this->_db->quoteIdentifier('product_id');

        $result = $this->_db->fetchRow("SELECT * FROM $products WHERE $product_id > :id ORDER BY $product_id", array(":id"=>1));
        $this->assertEquals(2, count($result)); // count columns
        $this->assertEquals(2, $result['product_id']);
    }

    public function testAdapterInsert()
    {
        $row = array (
            'product_id'   => new Zend_Db_Expr($this->_db->quoteIdentifier('zfproducts_seq').'.NEXTVAL'),
            'product_name' => 'Solaris',
        );
        $rowsAffected = $this->_db->insert('zfproducts', $row);
        $this->assertEquals(1, $rowsAffected);
        $lastInsertId = $this->_db->lastInsertId('zfproducts', null); // implies 'zfproducts_seq'
        $lastSequenceId = $this->_db->lastSequenceId('zfproducts_seq');
        $this->assertEquals('4', (string) $lastInsertId, 'Expected new id to be 4');
        $this->assertEquals('4', (string) $lastSequenceId, 'Expected new id to be 4');
    }

    public function testAdapterInsertDbExpr()
    {
        $row = array (
            'product_id'   => new Zend_Db_Expr($this->_db->quoteIdentifier('zfproducts_seq').'.NEXTVAL'),
            'product_name' => new Zend_Db_Expr('UPPER(\'Solaris\')')
        );
        $rowsAffected = $this->_db->insert('zfproducts', $row);
        $this->assertEquals(1, $rowsAffected);
        $product_id = $this->_db->quoteIdentifier('product_id', true);
        $select = $this->_db->select()
            ->from('zfproducts')
            ->where("$product_id = 4");
        $result = $this->_db->fetchAll($select);
        $this->assertType('array', $result);
        $this->assertEquals('SOLARIS', $result[0]['product_name']);
    }

    /**
     * Test the Adapter's limit() method.
     * Fetch 1 row.  Then fetch 1 row offset by 1 row.
     */
    public function testAdapterLimit()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');

        $sql = $this->_db->limit("SELECT * FROM $products", 1);

        $stmt = $this->_db->query($sql);
        $result = $stmt->fetchAll();
        $this->assertEquals(1, count($result),
            'Expecting row count to be 1');
        $this->assertEquals(3, count($result[0]),
            'Expecting column count to be 3');
        $this->assertEquals(1, $result[0]['product_id'],
            'Expecting to get product_id 1');
    }

    public function testAdapterLimitOffset()
    {
        $products = $this->_db->quoteIdentifier('zfproducts');

        $sql = $this->_db->limit("SELECT * FROM $products", 1, 1);

        $stmt = $this->_db->query($sql);
        $result = $stmt->fetchAll();
        $this->assertEquals(1, count($result),
            'Expecting row count to be 1');
        $this->assertEquals(3, count($result[0]),
            'Expecting column count to be 3');
        $this->assertEquals(2, $result[0]['product_id'],
            'Expecting to get product_id 2');
    }

    /**
     * Test that quote() takes an array and returns
     * an imploded string of comma-separated, quoted elements.
     */
    public function testAdapterQuoteArray()
    {
        $array = array("it's", 'all', 'right!');
        $value = $this->_db->quote($array);
        $this->assertEquals("'it''s', 'all', 'right!'", $value);
    }

    /**
     * test that quote() escapes a double-quote
     * character in a string.
     */
    public function testAdapterQuoteDoubleQuote()
    {
        $value = $this->_db->quote('St John"s Wort');
        $this->assertEquals("'St John\"s Wort'", $value);
    }

    /**
     * test that quote() escapes a single-quote
     * character in a string.
     */
    public function testAdapterQuoteSingleQuote()
    {
        $string = "St John's Wort";
        $value = $this->_db->quote($string);
        $this->assertEquals("'St John''s Wort'", $value);
    }

    /**
     * test that quoteInto() escapes a double-quote
     * character in a string.
     */
    public function testAdapterQuoteIntoDoubleQuote()
    {
        $value = $this->_db->quoteInto('id=?', 'St John"s Wort');
        $this->assertEquals("id='St John\"s Wort'", $value);
    }

    /**
     * test that quoteInto() escapes a single-quote
     * character in a string.
     */
    public function testAdapterQuoteIntoSingleQuote()
    {
        $value = $this->_db->quoteInto('id = ?', 'St John\'s Wort');
        $this->assertEquals("id = 'St John''s Wort'", $value);
    }

    /**
     * test that quoteTableAs() accepts a string and an alias,
     * and returns each as delimited identifiers.
     * Oracle does not want the 'AS' in between.
     */
    public function testAdapterQuoteTableAs()
    {
        $string = "foo";
        $alias = "bar";
        $value = $this->_db->quoteTableAs($string, $alias);
        $this->assertEquals('"foo" "bar"', $value);
    }

    /**
     * Used by _testAdapterOptionCaseFoldingNatural()
     * DB2 and Oracle return identifiers in uppercase naturally,
     * so those test suites will override this method.
     */
    protected function _testAdapterOptionCaseFoldingNaturalIdentifier()
    {
        return 'CASE_FOLDED_IDENTIFIER';
    }

    public function testAdapterOptionCaseFoldingUpper()
    {
        $this->markTestIncomplete($this->getDriver() . ' does not support case-folding array keys yet.');
    }

    public function testAdapterOptionCaseFoldingLower()
    {
        $this->markTestIncomplete($this->getDriver() . ' does not support case-folding array keys yet.');
    }

    public function testAdapterTransactionCommit()
    {
        $this->markTestIncomplete($this->getDriver() . ' is having trouble with transactions');
    }

    public function testAdapterTransactionRollback()
    {
        $this->markTestIncomplete($this->getDriver() . ' is having trouble with transactions');
    }

    public function testAdapterAlternateStatement()
    {
        $this->_testAdapterAlternateStatement('Test_OracleStatement');
    }

    public function getDriver()
    {
        return 'Oracle';
    }

}
