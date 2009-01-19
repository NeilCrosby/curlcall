<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/../CurlCallTestSuite.php');

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_GetFromXmlSourceTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->obj = new CurlCall();
    }

    /**
     * @dataProvider CurlCallTestSuite::validXmlGetReturningSourceProvider
     */
    public function testSendsDataViaGet($input, $output) {
        $pieces = explode('?', $input);
        $expectedSize = sizeof(explode('&', $pieces[1]));

        $result = $this->obj->getFromXmlSource($input);
        $this->assertEquals(
            $expectedSize,
            sizeof($result)
        );

        // now ask for the post array instead. It should be empty
        $input = str_replace('=get', '=post', $input);
        $result = $this->obj->getFromXmlSource($input);
        $this->assertEquals(
            0,
            sizeof($result)
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validXmlSourceProvider
     */
    public function testReturnsSimpleXmlIfSingleValidUrlGiven($input, $output) {
        $result = $this->obj->getFromXmlSource($input);
        
        $this->assertType(
            'SimpleXMLElement',
            $result
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validXmlSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        $url    = $input[0];
        $cookie = $input[1];
        
        $result = $this->obj->getFromXmlSource($url, array('curlopts'=>array(CURLOPT_COOKIE=>$cookie)));
        
        $intermediateResult = array();
        foreach ($result as $key=>$value) {
            foreach($value->attributes() as $key => $attrValue) {
                if ( 'key' == $key ) {
                    $actualKey = $attrValue;
                }
            }
            
            array_push($intermediateResult, "$actualKey=$value");
        }
        
        $concatenatedResult = implode(';', $intermediateResult);
        
        $this->assertEquals(
            $cookie,
            $concatenatedResult
        );
    }
}
?>