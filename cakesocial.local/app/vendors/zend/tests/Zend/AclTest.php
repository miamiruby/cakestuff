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
 * @package    Zend_Acl
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AclTest.php 9417 2008-05-08 16:28:31Z darby $
 */

/**
 * Test helper
 */
require_once dirname(__FILE__) . '/../TestHelper.php';

/**
 * Zend_Acl
 */
require_once 'Zend/Acl.php';

/**
 * Zend_Acl_Resource
 */
require_once 'Zend/Acl/Resource.php';

/**
 * Zend_Acl_Role
 */
require_once 'Zend/Acl/Role.php';

/**
 * @category   Zend
 * @package    Zend_Acl
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_AclTest extends PHPUnit_Framework_TestCase
{
    /**
     * ACL object for each test method
     *
     * @var Zend_Acl
     */
    protected $_acl;

    /**
     * Instantiates a new ACL object and creates internal reference to it for each test method
     *
     * @return void
     */
    public function setUp()
    {
        $this->_acl = new Zend_Acl();
    }

    /**
     * Ensures that basic addition and retrieval of a single Role works
     *
     * @return void
     */
    public function testRoleRegistryAddAndGetOne()
    {
        $roleGuest = new Zend_Acl_Role('guest');

        $role = $this->_acl->addRole($roleGuest)
                          ->getRole($roleGuest->getRoleId());
        $this->assertTrue($roleGuest === $role);
        $role = $this->_acl->getRole($roleGuest);
        $this->assertTrue($roleGuest === $role);
    }

    /**
     * Ensures that basic removal of a single Role works
     *
     * @return void
     */
    public function testRoleRegistryRemoveOne()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->removeRole($roleGuest);
        $this->assertFalse($this->_acl->hasRole($roleGuest));
    }

    /**
     * Ensures that an exception is thrown when a non-existent Role is specified for removal
     *
     * @return void
     */
    public function testRoleRegistryRemoveOneNonExistent()
    {
        try {
            $this->_acl->removeRole('nonexistent');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon removing a non-existent Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }

    /**
     * Ensures that removal of all Roles works
     *
     * @return void
     */
    public function testRoleRegistryRemoveAll()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->removeRoleAll();
        $this->assertFalse($this->_acl->hasRole($roleGuest));
    }

    /**
     * Ensures that an exception is thrown when a non-existent Role is specified as a parent upon Role addition
     *
     * @return void
     */
    public function testRoleRegistryAddInheritsNonExistent()
    {
        try {
            $this->_acl->addRole(new Zend_Acl_Role('guest'), 'nonexistent');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon specifying a non-existent parent');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('does not exist', $e->getMessage());
        }
    }

    /**
     * Ensures that an exception is thrown when a non-existent Role is specified to each parameter of inherits()
     *
     * @return void
     */
    public function testRoleRegistryInheritsNonExistent()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest);
        try {
            $this->_acl->inheritsRole('nonexistent', $roleGuest);
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon specifying a non-existent child Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        try {
            $this->_acl->inheritsRole($roleGuest, 'nonexistent');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon specifying a non-existent parent Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }

    /**
     * Tests basic Role inheritance
     *
     * @return void
     */
    public function testRoleRegistryInherits()
    {
        $roleGuest  = new Zend_Acl_Role('guest');
        $roleMember = new Zend_Acl_Role('member');
        $roleEditor = new Zend_Acl_Role('editor');
        $roleRegistry = new Zend_Acl_Role_Registry();
        $roleRegistry->add($roleGuest)
                    ->add($roleMember, $roleGuest->getRoleId())
                    ->add($roleEditor, $roleMember);
        $this->assertTrue(0 === count($roleRegistry->getParents($roleGuest)));
        $roleMemberParents = $roleRegistry->getParents($roleMember);
        $this->assertTrue(1 === count($roleMemberParents));
        $this->assertTrue(isset($roleMemberParents['guest']));
        $roleEditorParents = $roleRegistry->getParents($roleEditor);
        $this->assertTrue(1 === count($roleEditorParents));
        $this->assertTrue(isset($roleEditorParents['member']));
        $this->assertTrue($roleRegistry->inherits($roleMember, $roleGuest, true));
        $this->assertTrue($roleRegistry->inherits($roleEditor, $roleMember, true));
        $this->assertTrue($roleRegistry->inherits($roleEditor, $roleGuest));
        $this->assertFalse($roleRegistry->inherits($roleGuest, $roleMember));
        $this->assertFalse($roleRegistry->inherits($roleMember, $roleEditor));
        $this->assertFalse($roleRegistry->inherits($roleGuest, $roleEditor));
        $roleRegistry->remove($roleMember);
        $this->assertTrue(0 === count($roleRegistry->getParents($roleEditor)));
        $this->assertFalse($roleRegistry->inherits($roleEditor, $roleGuest));
    }

    /**
     * Tests basic Role multiple inheritance
     *
     * @return void
     */
    public function testRoleRegistryInheritsMultiple()
    {
        $roleParent1 = new Zend_Acl_Role('parent1');
        $roleParent2 = new Zend_Acl_Role('parent2');
        $roleChild   = new Zend_Acl_Role('child');
        $roleRegistry = new Zend_Acl_Role_Registry();
        $roleRegistry->add($roleParent1)
                    ->add($roleParent2)
                    ->add($roleChild, array($roleParent1, $roleParent2));
        $roleChildParents = $roleRegistry->getParents($roleChild);
        $this->assertTrue(2 === count($roleChildParents));
        $i = 1;
        foreach ($roleChildParents as $roleParentId => $roleParent) {
            $this->assertTrue("parent$i" === $roleParentId);
            $i++;
        }
        $this->assertTrue($roleRegistry->inherits($roleChild, $roleParent1));
        $this->assertTrue($roleRegistry->inherits($roleChild, $roleParent2));
        $roleRegistry->remove($roleParent1);
        $roleChildParents = $roleRegistry->getParents($roleChild);
        $this->assertTrue(1 === count($roleChildParents));
        $this->assertTrue(isset($roleChildParents['parent2']));
        $this->assertTrue($roleRegistry->inherits($roleChild, $roleParent2));
    }

    /**
     * Ensures that the same Role cannot be registered more than once to the registry
     *
     * @return void
     */
    public function testRoleRegistryDuplicate()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $roleRegistry = new Zend_Acl_Role_Registry();
        try {
            $roleRegistry->add($roleGuest)
                        ->add($roleGuest);
            $this->fail('Expected exception not thrown upon adding same Role twice');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('already exists', $e->getMessage());
        }
    }

    /**
     * Ensures that two Roles having the same ID cannot be registered
     *
     * @return void
     */
    public function testRoleRegistryDuplicateId()
    {
        $roleGuest1 = new Zend_Acl_Role('guest');
        $roleGuest2 = new Zend_Acl_Role('guest');
        $roleRegistry = new Zend_Acl_Role_Registry();
        try {
            $roleRegistry->add($roleGuest1)
                        ->add($roleGuest2);
            $this->fail('Expected exception not thrown upon adding two Roles with same ID');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('already exists', $e->getMessage());
        }
    }

    /**
     * Ensures that basic addition and retrieval of a single Resource works
     *
     * @return void
     */
    public function testResourceAddAndGetOne()
    {
        $resourceArea = new Zend_Acl_Resource('area');
        $resource = $this->_acl->add($resourceArea)
                          ->get($resourceArea->getResourceId());
        $this->assertTrue($resourceArea === $resource);
        $resource = $this->_acl->get($resourceArea);
        $this->assertTrue($resourceArea === $resource);
    }

    /**
     * Ensures that basic removal of a single Resource works
     *
     * @return void
     */
    public function testResourceRemoveOne()
    {
        $resourceArea = new Zend_Acl_Resource('area');
        $this->_acl->add($resourceArea)
                   ->remove($resourceArea);
        $this->assertFalse($this->_acl->has($resourceArea));
    }

    /**
     * Ensures that an exception is thrown when a non-existent Resource is specified for removal
     *
     * @return void
     */
    public function testResourceRemoveOneNonExistent()
    {
        try {
            $this->_acl->remove('nonexistent');
            $this->fail('Expected Zend_Acl_Exception not thrown upon removing a non-existent Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }

    /**
     * Ensures that removal of all Resources works
     *
     * @return void
     */
    public function testResourceRemoveAll()
    {
        $resourceArea = new Zend_Acl_Resource('area');
        $this->_acl->add($resourceArea)
                   ->removeAll();
        $this->assertFalse($this->_acl->has($resourceArea));
    }

    /**
     * Ensures that an exception is thrown when a non-existent Resource is specified as a parent upon Resource addition
     *
     * @return void
     */
    public function testResourceAddInheritsNonExistent()
    {
        try {
            $this->_acl->add(new Zend_Acl_Resource('area'), 'nonexistent');
            $this->fail('Expected Zend_Acl_Exception not thrown upon specifying a non-existent parent');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('does not exist', $e->getMessage());
        }
    }

    /**
     * Ensures that an exception is thrown when a non-existent Resource is specified to each parameter of inherits()
     *
     * @return void
     */
    public function testResourceInheritsNonExistent()
    {
        $resourceArea = new Zend_Acl_Resource('area');
        $this->_acl->add($resourceArea);
        try {
            $this->_acl->inherits('nonexistent', $resourceArea);
            $this->fail('Expected Zend_Acl_Exception not thrown upon specifying a non-existent child Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        try {
            $this->_acl->inherits($resourceArea, 'nonexistent');
            $this->fail('Expected Zend_Acl_Exception not thrown upon specifying a non-existent parent Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }

    /**
     * Tests basic Resource inheritance
     *
     * @return void
     */
    public function testResourceInherits()
    {
        $resourceCity     = new Zend_Acl_Resource('city');
        $resourceBuilding = new Zend_Acl_Resource('building');
        $resourceRoom     = new Zend_Acl_Resource('room');
        $this->_acl->add($resourceCity)
                   ->add($resourceBuilding, $resourceCity->getResourceId())
                   ->add($resourceRoom, $resourceBuilding);
        $this->assertTrue($this->_acl->inherits($resourceBuilding, $resourceCity, true));
        $this->assertTrue($this->_acl->inherits($resourceRoom, $resourceBuilding, true));
        $this->assertTrue($this->_acl->inherits($resourceRoom, $resourceCity));
        $this->assertFalse($this->_acl->inherits($resourceCity, $resourceBuilding));
        $this->assertFalse($this->_acl->inherits($resourceBuilding, $resourceRoom));
        $this->assertFalse($this->_acl->inherits($resourceCity, $resourceRoom));
        $this->_acl->remove($resourceBuilding);
        $this->assertFalse($this->_acl->has($resourceRoom));
    }

    /**
     * Ensures that the same Resource cannot be added more than once
     *
     * @return void
     */
    public function testResourceDuplicate()
    {
        try {
            $resourceArea = new Zend_Acl_Resource('area');
            $this->_acl->add($resourceArea)
                       ->add($resourceArea);
            $this->fail('Expected exception not thrown upon adding same Resource twice');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('already exists', $e->getMessage());
        }
    }

    /**
     * Ensures that two Resources having the same ID cannot be added
     *
     * @return void
     */
    public function testResourceDuplicateId()
    {
        try {
            $resourceArea1 = new Zend_Acl_Resource('area');
            $resourceArea2 = new Zend_Acl_Resource('area');
            $this->_acl->add($resourceArea1)
                       ->add($resourceArea2);
            $this->fail('Expected exception not thrown upon adding two Resources with same ID');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('already exists', $e->getMessage());
        }
    }

    /**
     * Ensures that an exception is thrown when a non-existent Role and Resource parameters are specified to isAllowed()
     *
     * @return void
     */
    public function testIsAllowedNonExistent()
    {
        try {
            $this->_acl->isAllowed('nonexistent');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon non-existent Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        try {
            $this->_acl->isAllowed(null, 'nonexistent');
            $this->fail('Expected Zend_Acl_Exception not thrown upon non-existent Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
    }

    /**
     * Ensures that by default, Zend_Acl denies access to everything by all
     *
     * @return void
     */
    public function testDefaultDeny()
    {
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that the default rule obeys its assertion
     *
     * @return void
     */
    public function testDefaultAssert()
    {
        $this->_acl->deny(null, null, null, new Zend_AclTest_AssertFalse());
        $this->assertTrue($this->_acl->isAllowed());
        $this->assertTrue($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that ACL-wide rules (all Roles, Resources, and privileges) work properly
     *
     * @return void
     */
    public function testDefaultRuleSet()
    {
        $this->_acl->allow();
        $this->assertTrue($this->_acl->isAllowed());
        $this->_acl->deny();
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that by default, Zend_Acl denies access to a privilege on anything by all
     *
     * @return void
     */
    public function testDefaultPrivilegeDeny()
    {
        $this->assertFalse($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that ACL-wide rules apply to privileges
     *
     * @return void
     */
    public function testDefaultRuleSetPrivilege()
    {
        $this->_acl->allow();
        $this->assertTrue($this->_acl->isAllowed(null, null, 'somePrivilege'));
        $this->_acl->deny();
        $this->assertFalse($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that a privilege allowed for all Roles upon all Resources works properly
     *
     * @return void
     */
    public function testPrivilegeAllow()
    {
        $this->_acl->allow(null, null, 'somePrivilege');
        $this->assertTrue($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that a privilege denied for all Roles upon all Resources works properly
     *
     * @return void
     */
    public function testPrivilegeDeny()
    {
        $this->_acl->allow();
        $this->_acl->deny(null, null, 'somePrivilege');
        $this->assertFalse($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that multiple privileges work properly
     *
     * @return void
     */
    public function testPrivileges()
    {
        $this->_acl->allow(null, null, array('p1', 'p2', 'p3'));
        $this->assertTrue($this->_acl->isAllowed(null, null, 'p1'));
        $this->assertTrue($this->_acl->isAllowed(null, null, 'p2'));
        $this->assertTrue($this->_acl->isAllowed(null, null, 'p3'));
        $this->assertFalse($this->_acl->isAllowed(null, null, 'p4'));
        $this->_acl->deny(null, null, 'p1');
        $this->assertFalse($this->_acl->isAllowed(null, null, 'p1'));
        $this->_acl->deny(null, null, array('p2', 'p3'));
        $this->assertFalse($this->_acl->isAllowed(null, null, 'p2'));
        $this->assertFalse($this->_acl->isAllowed(null, null, 'p3'));
    }

    /**
     * Ensures that assertions on privileges work properly
     *
     * @return void
     */
    public function testPrivilegeAssert()
    {
        $this->_acl->allow(null, null, 'somePrivilege', new Zend_AclTest_AssertTrue());
        $this->assertTrue($this->_acl->isAllowed(null, null, 'somePrivilege'));
        $this->_acl->allow(null, null, 'somePrivilege', new Zend_AclTest_AssertFalse());
        $this->assertFalse($this->_acl->isAllowed(null, null, 'somePrivilege'));
    }

    /**
     * Ensures that by default, Zend_Acl denies access to everything for a particular Role
     *
     * @return void
     */
    public function testRoleDefaultDeny()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest);
        $this->assertFalse($this->_acl->isAllowed($roleGuest));
    }

    /**
     * Ensures that ACL-wide rules (all Resources and privileges) work properly for a particular Role
     *
     * @return void
     */
    public function testRoleDefaultRuleSet()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest);
        $this->assertTrue($this->_acl->isAllowed($roleGuest));
        $this->_acl->deny($roleGuest);
        $this->assertFalse($this->_acl->isAllowed($roleGuest));
    }

    /**
     * Ensures that by default, Zend_Acl denies access to a privilege on anything for a particular Role
     *
     * @return void
     */
    public function testRoleDefaultPrivilegeDeny()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest);
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }

    /**
     * Ensures that ACL-wide rules apply to privileges for a particular Role
     *
     * @return void
     */
    public function testRoleDefaultRuleSetPrivilege()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest);
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
        $this->_acl->deny($roleGuest);
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }

    /**
     * Ensures that a privilege allowed for a particular Role upon all Resources works properly
     *
     * @return void
     */
    public function testRolePrivilegeAllow()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest, null, 'somePrivilege');
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }

    /**
     * Ensures that a privilege denied for a particular Role upon all Resources works properly
     *
     * @return void
     */
    public function testRolePrivilegeDeny()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest)
                   ->deny($roleGuest, null, 'somePrivilege');
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }

    /**
     * Ensures that multiple privileges work properly for a particular Role
     *
     * @return void
     */
    public function testRolePrivileges()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest, null, array('p1', 'p2', 'p3'));
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'p1'));
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'p2'));
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'p3'));
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'p4'));
        $this->_acl->deny($roleGuest, null, 'p1');
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'p1'));
        $this->_acl->deny($roleGuest, null, array('p2', 'p3'));
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'p2'));
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'p3'));
    }

    /**
     * Ensures that assertions on privileges work properly for a particular Role
     *
     * @return void
     */
    public function testRolePrivilegeAssert()
    {
        $roleGuest = new Zend_Acl_Role('guest');
        $this->_acl->addRole($roleGuest)
                   ->allow($roleGuest, null, 'somePrivilege', new Zend_AclTest_AssertTrue());
        $this->assertTrue($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
        $this->_acl->allow($roleGuest, null, 'somePrivilege', new Zend_AclTest_AssertFalse());
        $this->assertFalse($this->_acl->isAllowed($roleGuest, null, 'somePrivilege'));
    }

    /**
     * Ensures that removing the default deny rule results in default deny rule
     *
     * @return void
     */
    public function testRemoveDefaultDeny()
    {
        $this->assertFalse($this->_acl->isAllowed());
        $this->_acl->removeDeny();
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that removing the default deny rule results in assertion method being removed
     *
     * @return void
     */
    public function testRemoveDefaultDenyAssert()
    {
        $this->_acl->deny(null, null, null, new Zend_AclTest_AssertFalse());
        $this->assertTrue($this->_acl->isAllowed());
        $this->_acl->removeDeny();
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that removing the default allow rule results in default deny rule being assigned
     *
     * @return void
     */
    public function testRemoveDefaultAllow()
    {
        $this->_acl->allow();
        $this->assertTrue($this->_acl->isAllowed());
        $this->_acl->removeAllow();
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that removing non-existent default allow rule does nothing
     *
     * @return void
     */
    public function testRemoveDefaultAllowNonExistent()
    {
        $this->_acl->removeAllow();
        $this->assertFalse($this->_acl->isAllowed());
    }

    /**
     * Ensures that removing non-existent default deny rule does nothing
     *
     * @return void
     */
    public function testRemoveDefaultDenyNonExistent()
    {
        $this->_acl->allow()
                   ->removeDeny();
        $this->assertTrue($this->_acl->isAllowed());
    }

    /**
     * Ensures that for a particular Role, a deny rule on a specific Resource is honored before an allow rule
     * on the entire ACL
     *
     * @return void
     */
    public function testRoleDefaultAllowRuleWithResourceDenyRule()
    {
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                   ->addRole(new Zend_Acl_Role('staff'), 'guest')
                   ->add(new Zend_Acl_Resource('area1'))
                   ->add(new Zend_Acl_Resource('area2'))
                   ->deny()
                   ->allow('staff')
                   ->deny('staff', array('area1', 'area2'));
        $this->assertFalse($this->_acl->isAllowed('staff', 'area1'));
    }

    /**
     * Ensures that for a particular Role, a deny rule on a specific privilege is honored before an allow
     * rule on the entire ACL
     *
     * @return void
     */
    public function testRoleDefaultAllowRuleWithPrivilegeDenyRule()
    {
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                   ->addRole(new Zend_Acl_Role('staff'), 'guest')
                   ->deny()
                   ->allow('staff')
                   ->deny('staff', null, array('privilege1', 'privilege2'));
        $this->assertFalse($this->_acl->isAllowed('staff', null, 'privilege1'));
    }

    /**
     * Ensure that basic rule removal works
     *
     * @return void
     */
    public function testRulesRemove()
    {
        $this->_acl->allow(null, null, array('privilege1', 'privilege2'));
        $this->assertFalse($this->_acl->isAllowed());
        $this->assertTrue($this->_acl->isAllowed(null, null, 'privilege1'));
        $this->assertTrue($this->_acl->isAllowed(null, null, 'privilege2'));
        $this->_acl->removeAllow(null, null, 'privilege1');
        $this->assertFalse($this->_acl->isAllowed(null, null, 'privilege1'));
        $this->assertTrue($this->_acl->isAllowed(null, null, 'privilege2'));
    }

    /**
     * Ensures that removal of a Role results in its rules being removed
     *
     * @return void
     */
    public function testRuleRoleRemove()
    {
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                   ->allow('guest');
        $this->assertTrue($this->_acl->isAllowed('guest'));
        $this->_acl->removeRole('guest');
        try {
            $this->_acl->isAllowed('guest');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon isAllowed() on non-existent Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        $this->_acl->addRole(new Zend_Acl_Role('guest'));
        $this->assertFalse($this->_acl->isAllowed('guest'));
    }

    /**
     * Ensures that removal of all Roles results in Role-specific rules being removed
     *
     * @return void
     */
    public function testRuleRoleRemoveAll()
    {
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                   ->allow('guest');
        $this->assertTrue($this->_acl->isAllowed('guest'));
        $this->_acl->removeRoleAll();
        try {
            $this->_acl->isAllowed('guest');
            $this->fail('Expected Zend_Acl_Role_Registry_Exception not thrown upon isAllowed() on non-existent Role');
        } catch (Zend_Acl_Role_Registry_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        $this->_acl->addRole(new Zend_Acl_Role('guest'));
        $this->assertFalse($this->_acl->isAllowed('guest'));
    }

    /**
     * Ensures that removal of a Resource results in its rules being removed
     *
     * @return void
     */
    public function testRulesResourceRemove()
    {
        $this->_acl->add(new Zend_Acl_Resource('area'))
                   ->allow(null, 'area');
        $this->assertTrue($this->_acl->isAllowed(null, 'area'));
        $this->_acl->remove('area');
        try {
            $this->_acl->isAllowed(null, 'area');
            $this->fail('Expected Zend_Acl_Exception not thrown upon isAllowed() on non-existent Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        $this->_acl->add(new Zend_Acl_Resource('area'));
        $this->assertFalse($this->_acl->isAllowed(null, 'area'));
    }

    /**
     * Ensures that removal of all Resources results in Resource-specific rules being removed
     *
     * @return void
     */
    public function testRulesResourceRemoveAll()
    {
        $this->_acl->add(new Zend_Acl_Resource('area'))
                   ->allow(null, 'area');
        $this->assertTrue($this->_acl->isAllowed(null, 'area'));
        $this->_acl->removeAll();
        try {
            $this->_acl->isAllowed(null, 'area');
            $this->fail('Expected Zend_Acl_Exception not thrown upon isAllowed() on non-existent Resource');
        } catch (Zend_Acl_Exception $e) {
            $this->assertContains('not found', $e->getMessage());
        }
        $this->_acl->add(new Zend_Acl_Resource('area'));
        $this->assertFalse($this->_acl->isAllowed(null, 'area'));
    }

    /**
     * Ensures that an example for a content management system is operable
     *
     * @return void
     */
    public function testCMSExample()
    {
        // Add some roles to the Role registry
        $this->_acl->addRole(new Zend_Acl_Role('guest'))
                   ->addRole(new Zend_Acl_Role('staff'), 'guest')  // staff inherits permissions from guest
                   ->addRole(new Zend_Acl_Role('editor'), 'staff') // editor inherits permissions from staff
                   ->addRole(new Zend_Acl_Role('administrator'));

        // Guest may only view content
        $this->_acl->allow('guest', null, 'view');

        // Staff inherits view privilege from guest, but also needs additional privileges
        $this->_acl->allow('staff', null, array('edit', 'submit', 'revise'));

        // Editor inherits view, edit, submit, and revise privileges, but also needs additional privileges
        $this->_acl->allow('editor', null, array('publish', 'archive', 'delete'));

        // Administrator inherits nothing but is allowed all privileges
        $this->_acl->allow('administrator');

        // Access control checks based on above permission sets

        $this->assertTrue($this->_acl->isAllowed('guest', null, 'view'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'edit'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'submit'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'revise'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'publish'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'archive'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'delete'));
        $this->assertFalse($this->_acl->isAllowed('guest', null, 'unknown'));
        $this->assertFalse($this->_acl->isAllowed('guest'));

        $this->assertTrue($this->_acl->isAllowed('staff', null, 'view'));
        $this->assertTrue($this->_acl->isAllowed('staff', null, 'edit'));
        $this->assertTrue($this->_acl->isAllowed('staff', null, 'submit'));
        $this->assertTrue($this->_acl->isAllowed('staff', null, 'revise'));
        $this->assertFalse($this->_acl->isAllowed('staff', null, 'publish'));
        $this->assertFalse($this->_acl->isAllowed('staff', null, 'archive'));
        $this->assertFalse($this->_acl->isAllowed('staff', null, 'delete'));
        $this->assertFalse($this->_acl->isAllowed('staff', null, 'unknown'));
        $this->assertFalse($this->_acl->isAllowed('staff'));

        $this->assertTrue($this->_acl->isAllowed('editor', null, 'view'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'edit'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'submit'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'revise'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'publish'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'archive'));
        $this->assertTrue($this->_acl->isAllowed('editor', null, 'delete'));
        $this->assertFalse($this->_acl->isAllowed('editor', null, 'unknown'));
        $this->assertFalse($this->_acl->isAllowed('editor'));

        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'view'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'edit'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'submit'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'revise'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'publish'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'archive'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'delete'));
        $this->assertTrue($this->_acl->isAllowed('administrator', null, 'unknown'));
        $this->assertTrue($this->_acl->isAllowed('administrator'));

        // Some checks on specific areas, which inherit access controls from the root ACL node
        $this->_acl->add(new Zend_Acl_Resource('newsletter'))
                   ->add(new Zend_Acl_Resource('pending'), 'newsletter')
                   ->add(new Zend_Acl_Resource('gallery'))
                   ->add(new Zend_Acl_Resource('profiles', 'gallery'))
                   ->add(new Zend_Acl_Resource('config'))
                   ->add(new Zend_Acl_Resource('hosts'), 'config');
        $this->assertTrue($this->_acl->isAllowed('guest', 'pending', 'view'));
        $this->assertTrue($this->_acl->isAllowed('staff', 'profiles', 'revise'));
        $this->assertTrue($this->_acl->isAllowed('staff', 'pending', 'view'));
        $this->assertTrue($this->_acl->isAllowed('staff', 'pending', 'edit'));
        $this->assertFalse($this->_acl->isAllowed('staff', 'pending', 'publish'));
        $this->assertFalse($this->_acl->isAllowed('staff', 'pending'));
        $this->assertFalse($this->_acl->isAllowed('editor', 'hosts', 'unknown'));
        $this->assertTrue($this->_acl->isAllowed('administrator', 'pending'));

        // Add a new group, marketing, which bases its permissions on staff
        $this->_acl->addRole(new Zend_Acl_Role('marketing'), 'staff');

        // Refine the privilege sets for more specific needs

        // Allow marketing to publish and archive newsletters
        $this->_acl->allow('marketing', 'newsletter', array('publish', 'archive'));

        // Allow marketing to publish and archive latest news
        $this->_acl->add(new Zend_Acl_Resource('news'))
                   ->add(new Zend_Acl_Resource('latest'), 'news');
        $this->_acl->allow('marketing', 'latest', array('publish', 'archive'));

        // Deny staff (and marketing, by inheritance) rights to revise latest news
        $this->_acl->deny('staff', 'latest', 'revise');

        // Deny everyone access to archive news announcements
        $this->_acl->add(new Zend_Acl_Resource('announcement'), 'news');
        $this->_acl->deny(null, 'announcement', 'archive');

        // Access control checks for the above refined permission sets

        $this->assertTrue($this->_acl->isAllowed('marketing', null, 'view'));
        $this->assertTrue($this->_acl->isAllowed('marketing', null, 'edit'));
        $this->assertTrue($this->_acl->isAllowed('marketing', null, 'submit'));
        $this->assertTrue($this->_acl->isAllowed('marketing', null, 'revise'));
        $this->assertFalse($this->_acl->isAllowed('marketing', null, 'publish'));
        $this->assertFalse($this->_acl->isAllowed('marketing', null, 'archive'));
        $this->assertFalse($this->_acl->isAllowed('marketing', null, 'delete'));
        $this->assertFalse($this->_acl->isAllowed('marketing', null, 'unknown'));
        $this->assertFalse($this->_acl->isAllowed('marketing'));

        $this->assertTrue($this->_acl->isAllowed('marketing', 'newsletter', 'publish'));
        $this->assertFalse($this->_acl->isAllowed('staff', 'pending', 'publish'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'pending', 'publish'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'newsletter', 'archive'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'newsletter', 'delete'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'newsletter'));

        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'publish'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'archive'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'latest', 'delete'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'latest', 'revise'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'latest'));

        $this->assertFalse($this->_acl->isAllowed('marketing', 'announcement', 'archive'));
        $this->assertFalse($this->_acl->isAllowed('staff', 'announcement', 'archive'));
        $this->assertFalse($this->_acl->isAllowed('administrator', 'announcement', 'archive'));

        $this->assertFalse($this->_acl->isAllowed('staff', 'latest', 'publish'));
        $this->assertFalse($this->_acl->isAllowed('editor', 'announcement', 'archive'));

        // Remove some previous permission specifications

        // Marketing can no longer publish and archive newsletters
        $this->_acl->removeAllow('marketing', 'newsletter', array('publish', 'archive'));

        // Marketing can no longer archive the latest news
        $this->_acl->removeAllow('marketing', 'latest', 'archive');

        // Now staff (and marketing, by inheritance) may revise latest news
        $this->_acl->removeDeny('staff', 'latest', 'revise');

        // Access control checks for the above refinements

        $this->assertFalse($this->_acl->isAllowed('marketing', 'newsletter', 'publish'));
        $this->assertFalse($this->_acl->isAllowed('marketing', 'newsletter', 'archive'));

        $this->assertFalse($this->_acl->isAllowed('marketing', 'latest', 'archive'));

        $this->assertTrue($this->_acl->isAllowed('staff', 'latest', 'revise'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'revise'));

        // Grant marketing all permissions on the latest news
        $this->_acl->allow('marketing', 'latest');

        // Access control checks for the above refinement
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'archive'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'publish'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest', 'edit'));
        $this->assertTrue($this->_acl->isAllowed('marketing', 'latest'));

    }

    /**
     * Ensures that the $onlyParents argument to inheritsRole() works
     *
     * @return void
     * @see    http://framework.zend.com/issues/browse/ZF-2502
     */
    public function testRoleInheritanceSupportsCheckingOnlyParents()
    {
        $this->_acl->addRole(new Zend_Acl_Role('grandparent'))
                   ->addRole(new Zend_Acl_Role('parent'), 'grandparent')
                   ->addRole(new Zend_Acl_Role('child'), 'parent');
        $this->assertFalse($this->_acl->inheritsRole('child', 'grandparent', true));
    }

    /**
     * Ensures that the solution for ZF-2234 works as expected
     *
     * @return void
     * @see    http://framework.zend.com/issues/browse/ZF-2234
     */
    public function testZf2234()
    {
        $acl = new Zend_AclTest_ExtensionForZf2234();

        $someResource = new Zend_Acl_Resource('someResource');
        $someRole     = new Zend_Acl_Role('someRole');

        $acl->add($someResource)
            ->addRole($someRole);

        $nullValue     = null;
        $nullReference =& $nullValue;

        try {
            $acl->roleDFSVisitAllPrivileges($someRole, $someResource, $nullReference);
            $this->fail('Expected Zend_Acl_Exception not thrown');
        } catch (Zend_Acl_Exception $e) {
            $this->assertEquals('$dfs parameter may not be null', $e->getMessage());
        }

        try {
            $acl->roleDFSOnePrivilege($someRole, $someResource, null);
            $this->fail('Expected Zend_Acl_Exception not thrown');
        } catch (Zend_Acl_Exception $e) {
            $this->assertEquals('$privilege parameter may not be null', $e->getMessage());
        }

        try {
            $acl->roleDFSVisitOnePrivilege($someRole, $someResource, null);
            $this->fail('Expected Zend_Acl_Exception not thrown');
        } catch (Zend_Acl_Exception $e) {
            $this->assertEquals('$privilege parameter may not be null', $e->getMessage());
        }

        try {
            $acl->roleDFSVisitOnePrivilege($someRole, $someResource, 'somePrivilege', $nullReference);
            $this->fail('Expected Zend_Acl_Exception not thrown');
        } catch (Zend_Acl_Exception $e) {
            $this->assertEquals('$dfs parameter may not be null', $e->getMessage());
        }
    }

}


class Zend_AclTest_AssertFalse implements Zend_Acl_Assert_Interface
{
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
       return false;
    }
}


class Zend_AclTest_AssertTrue implements Zend_Acl_Assert_Interface
{
    public function assert(Zend_Acl $acl, Zend_Acl_Role_Interface $role = null, Zend_Acl_Resource_Interface $resource = null,
                           $privilege = null)
    {
       return true;
    }
}

class Zend_AclTest_ExtensionForZf2234 extends Zend_Acl
{
    public function roleDFSVisitAllPrivileges(Zend_Acl_Role_Interface $role, Zend_Acl_Resource_Interface $resource = null,
                                              &$dfs = null)
    {
        return $this->_roleDFSVisitAllPrivileges($role, $resource, $dfs);
    }

    public function roleDFSOnePrivilege(Zend_Acl_Role_Interface $role, Zend_Acl_Resource_Interface $resource = null,
                                        $privilege = null)
    {
        return $this->_roleDFSOnePrivilege($role, $resource, $privilege);
    }

    public function roleDFSVisitOnePrivilege(Zend_Acl_Role_Interface $role, Zend_Acl_Resource_Interface $resource = null,
                                             $privilege = null, &$dfs = null)
    {
        return $this->_roleDFSVisitOnePrivilege($role, $resource, $privilege, $dfs);
    }
}