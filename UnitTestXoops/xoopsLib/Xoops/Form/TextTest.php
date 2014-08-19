<?php
namespace Xoops\Form;

require_once(dirname(__FILE__).'/../../../init_mini.php');

/**
 * PHPUnit special settings :
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */

/**
 * Generated by PHPUnit_SkeletonGenerator on 2014-08-18 at 21:59:26.
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Text
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Text('Caption', 'name', 10, 20);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Xoops\Form\Text::getSize
     * @todo   Implement testGetSize().
     */
    public function testGetSize()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Form\Text::getMaxlength
     * @todo   Implement testGetMaxlength().
     */
    public function testGetMaxlength()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Form\Text::getPlaceholder
     * @todo   Implement testGetPlaceholder().
     */
    public function testGetPlaceholder()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Xoops\Form\Text::render
     * @todo   Implement testRender().
     */
    public function testRender()
    {
        $value = $this->object->render();
        $this->assertTrue(is_string($value));
    }
}
