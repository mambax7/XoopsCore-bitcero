<?php
namespace Xmf\Template;

require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.1 on 2014-05-22 at 19:56:37.
 */
 
/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

class AbstractTemplateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractTemplate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('Xmf\Template\AbstractTemplate');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xmf\Template\AbstractTemplate::fetch
     * @todo   Implement testFetch().
     */
    public function testFetch()
    {
        $ret = $this->object->fetch();
        $this->assertTrue(is_scalar($ret));
    }
}
