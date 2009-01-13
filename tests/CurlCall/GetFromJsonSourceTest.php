<?php

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_GetFromJsonSourceTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->obj = new CurlCall();
    }

    /**
     * @dataProvider CurlCallTestSuite::validJsonSourceProvider
     */
    public function testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output) {
        $result = $this->obj->getFromJsonSource($input);
        $this->assertType(
            $output,
            $result
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validJsonSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        $url    = $input[0];
        $cookie = $input[1];
        
        $result = $this->obj->getFromJsonSource($url, array('curlopts'=>array(CURLOPT_COOKIE=>$cookie)));
        
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