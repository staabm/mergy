<?php
/**
 * Test class for Mergy_Util_Parallel_Transport_Builder.
 * Generated by PHPUnit on 2012-03-24 at 14:59:34.
 */
class Mergy_Util_Parallel_Transport_BuilderTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider buildProvider
     */
    public function testBuild($sTransport, $sExpected) {
        $oTransport = Mergy_Util_Parallel_Transport_Builder::build($sTransport);
        $this->assertInstanceOf('Mergy_Util_Parallel_TransportInterface', $oTransport);
        $this->assertInstanceOf($sExpected, $oTransport);
    }

    /**
     * Data provider for build-test
     *
     * @return array
     */
    public function buildProvider() {
        return array(
            array(
                'shared',
                Mergy_Util_Parallel_Transport_Builder::TRANSPORT_SHARED
            ),
            array(
                'file',
                Mergy_Util_Parallel_Transport_Builder::TRANSPORT_FILE
            ),
            array(
                'memcache',
                Mergy_Util_Parallel_Transport_Builder::TRANSPORT_MEMCACHE
            ),
            array(
                Mergy_Util_Parallel_Transport_Builder::TRANSPORT_DEFAULT,
                Mergy_Util_Parallel_Transport_Builder::TRANSPORT_SHARED
            )
        );
    }

    /**
     * Test that a unknown transport throws an exception
     */
    public function testBuildException() {
        try {
            Mergy_Util_Parallel_Transport_Builder::build('blafasel');
            $this->fail('An exception should have been thrown, when creating a unknown Transport');
        }
        catch (Mergy_Util_Parallel_Transport_Exception $oException) {
            $this->assertEquals($oException->getMessage(), Mergy_Util_Parallel_Transport_Exception::UNKNOWN_TRANSPORT);
        }
    }
}