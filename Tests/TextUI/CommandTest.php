<?php
/**
 * Test class for Mergy_TextUI_Command.
 * Generated by PHPUnit on 2011-12-16 at 15:25:52.
 */
class Mergy_TextUI_CommandTest extends PHPUnit_Framework_TestCase {

    /**
     * The test dummy
     *
     * @var Mergy_TextUI_Command
     */
    protected $_object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    public function setUp() {
        $this->_object = new Mergy_TextUI_Command();
    }

    /**
     *
     */
    public function testHandleArguments() {

    }

    /**
     * Test the usage-string
     */
    public function testShowHelp() {
        $this->expectOutputString(Mergy_TextUI_Command::USAGE . PHP_EOL);
        Mergy_TextUI_Command::showHelp();
    }

    /**
     * Test the usage-string
     */
    public function testShowHelpArgument() {
        $this->expectOutputString(Mergy_TextUI_Command::USAGE . PHP_EOL);
        $aArguments = array(
            '--help'
        );
        $this->assertFalse($this->_object->handleArguments($aArguments));
    }

    /**
     * Test the version-string
     */
    public function testPrintVersionString() {
        $this->expectOutputString(Mergy_TextUI_Command::VERSION . PHP_EOL);
        Mergy_TextUI_Command::printVersionString();
    }

    /**
     * Test the version-string
     */
    public function testPrintVersionStringArgument() {
        $this->expectOutputString(Mergy_TextUI_Command::VERSION . PHP_EOL);
        $aArguments = array(
            '--version'
        );
        $this->assertFalse($this->_object->handleArguments($aArguments));
    }
}