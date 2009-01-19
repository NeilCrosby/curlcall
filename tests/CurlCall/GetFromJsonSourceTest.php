<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/../CurlCallTestSuite.php');

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
     * @dataProvider CurlCallTestSuite::validJsonGetReturningSourceProvider
     */
    public function testSendsDataViaGet($input, $output) {
        $pieces = explode('?', $input);
        $expectedSize = sizeof(explode('&', $pieces[1]));

        $result = $this->obj->getFromJsonSource($input);
        $this->assertEquals(
            $expectedSize,
            sizeof($result)
        );

        // now ask for the post array instead. It should be empty
        $input = str_replace('=get', '=post', $input);
        $result = $this->obj->getFromJsonSource($input);
        $this->assertEquals(
            0,
            sizeof($result)
        );
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

    /**
     * @dataProvider CurlCallTestSuite::validJsonSourceProvider
     */
    public function testUsesCacheWhereApplicable($input) {
        $delay = 10000;
        
        $timeBefore = microtime(true);
        $result = $this->obj->getFromJsonSource($input."&delay=$delay");
        $this->assertGreaterThanOrEqual(
            $delay,
            1000000 * (microtime(true) - $timeBefore)
        );

        $timeBefore = microtime(true);
        $result = $this->obj->getFromJsonSource($input."&delay=$delay");
        $this->assertLessThan(
            $delay,
            1000000 * (microtime(true) - $timeBefore)
        );
        
    }

}

?>