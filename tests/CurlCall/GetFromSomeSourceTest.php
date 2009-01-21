<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/../CurlCallTestSuite.php');

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
abstract class CurlCall_GetFromSomeSourceTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        $this->obj = new CurlCall();
    }

    /**
     * @dataProvider CurlCallTestSuite::validGetReturningSourceProvider
     */
    public function testSendsDataViaGet($input, $output) {
        $pieces = explode('?', $input);
        $expectedSize = sizeof(explode('&', $pieces[1]));

        $result = $this->obj->{$this->method}($input);
        $this->assertEquals(
            $expectedSize,
            sizeof($result)
        );

        // now ask for the post array instead. It should be empty
        $input = str_replace('=get', '=post', $input);
        $result = $this->obj->{$this->method}($input);
        $this->assertEquals(
            0,
            sizeof($result)
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validSourceProvider
     */
    public function testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output) {
        $result = $this->obj->{$this->method}($input);
        $this->assertType(
            $output,
            $result
        );
    }

    /**
     * @dataProvider CurlCallTestSuite::validSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        $url    = $input[0];
        $cookie = $input[1];
        
        $result = $this->obj->{$this->method}($url, array('curlopts'=>array(CURLOPT_COOKIE=>$cookie)));
        
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
     * @dataProvider CurlCallTestSuite::validSourceProvider
     */
    public function testUsesCacheWhereApplicable($input) {
        $delay = 10000;
        
        $timeBefore = microtime(true);
        $result = $this->obj->{$this->method}($input."&delay=$delay");
        $this->assertGreaterThanOrEqual(
            $delay,
            1000000 * (microtime(true) - $timeBefore)
        );

        $timeBefore = microtime(true);
        $result = $this->obj->{$this->method}($input."&delay=$delay");
        $this->assertLessThan(
            $delay,
            1000000 * (microtime(true) - $timeBefore)
        );
        
    }

}
?>