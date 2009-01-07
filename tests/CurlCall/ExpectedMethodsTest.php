<?php

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_ExpectedMethodsTest extends TheCodeTrainBaseExpectedMethodsTestCase {
    public function setUp() {
        $this->class = 'CurlCall';
        $this->classFile = __FILE__;
    }

    public function tearDown() {
    }

    public static function expectedMethodsProvider() {
        return array(
            array('__construct'),
            array('getFromPhpSource'),
            array('getFromJsonSource'),
            array('getFromXmlSource'),
            array('getFromPhpSourceAsPost'),
        );
    }
}
?>