<?php

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_ConstructTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
    }

    public function tearDown() {
    }

    public function testCreatesInstanceOfCurlCall() {
        $obj = new CurlCall();
        $this->assertThat(
            $obj,
            $this->isInstanceOf('CurlCall')
        );
    }

}
?>