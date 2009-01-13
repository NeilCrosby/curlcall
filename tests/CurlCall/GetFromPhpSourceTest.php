<?php

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_GetFromPhpSourceTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->obj = new CurlCall();
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceProvider
     */
    public function testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output) {
        $result = $this->obj->getFromPhpSource($input);
        $this->assertType(
            $output,
            $result
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        $url    = $input[0];
        $cookie = $input[1];
        
        $result = $this->obj->getFromPhpSource($url, array('curlopts'=>array(CURLOPT_COOKIE=>$cookie)));
        
        $intermediateResult = array();
        foreach ($result as $key=>$value) {
            array_push($intermediateResult, "$key=$value");
        }
        
        $concatenatedResult = implode(';', $intermediateResult);
        
        $this->assertEquals(
            $cookie,
            $concatenatedResult
        );
    }

}
?>