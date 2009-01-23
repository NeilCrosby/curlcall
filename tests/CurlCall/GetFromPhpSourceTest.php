<?php

require_once(pathinfo(__FILE__, PATHINFO_DIRNAME).'/../CurlCallTestSuite.php');

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall_GetFromPhpSourceTest extends CurlCall_GetFromSomeSourceTest {
    public function setUp() {
        parent::setUp();
        $this->method = 'getFromPhpSource';
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpGetReturningSourceProvider
     */
    public function testSendsDataViaGet($input, $output) {
        parent::testSendsDataViaGet($input, $output);
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceProvider
     */
    public function testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output) {
        parent::testReturnsExpectedDataTypeIfSingleValidUrlGiven($input, $output);
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceCookieProvider
     */
    public function testCookieDataSentIfRequested($input) {
        parent::testCookieDataSentIfRequested($input);
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpSourceProvider
     */
    public function testUsesCacheWhereApplicable($input) {
        parent::testUsesCacheWhereApplicable($input);
    }

    /**
     * @dataProvider CurlCallTestSuite::validPhpMultipleSourcesProvider
     */
    public function testReturnsArrayOfExpectedDataTypesIfMultipleValidUrlsGiven($input, $output) {
        parent::testReturnsArrayOfExpectedDataTypesIfMultipleValidUrlsGiven($input, $output);
    }
}
?>