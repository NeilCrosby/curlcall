<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/../CurlCallTestSuite.php');

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_GetFromPhpSourceAsPostTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->obj = new CurlCall();
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpPostReturningPostSourceProvider
     */
    public function testSendsDataViaPost($input, $output) {
        $url  = $input[0];
        $post = $input[1];
        $expectedSize = sizeof(explode('&', $post));

        $result = $this->obj->getFromPhpSourceAsPost($url, array('post-fields'=>$post));
        $this->assertEquals(
            $expectedSize,
            sizeof($result)
        );


        // now ask for the get array instead. It should be empty
        $post = str_replace('=post', '=get', $post);
        $result = $this->obj->getFromPhpSourceAsPost($url, array('post-fields'=>$post));
        $this->assertEquals(
            0,
            sizeof($result)
        );
    }
    
    /**
     * @dataProvider CurlCallTestSuite::validPhpPostSourceProvider
     */
    public function testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output) {
        $url  = $input[0];
        $post = $input[1];
        
        $result = $this->obj->getFromPhpSourceAsPost($url, array('post-fields'=>$post));
        $this->assertType(
            $output,
            $result
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        $this->markTestIncomplete();
/*        $url    = $input[0];
        $cookie = $input[1];
        
        $result = $this->obj->getFromPhpSourceAsPost($url, array('curlopts'=>array(CURLOPT_COOKIE=>$cookie)));
        
        $intermediateResult = array();
        foreach ($result as $key=>$value) {
            array_push($intermediateResult, "$key=$value");
        }
        
        $concatenatedResult = implode(';', $intermediateResult);
        
        $this->assertEquals(
            $cookie,
            $concatenatedResult
        );*/
    }

}
?>