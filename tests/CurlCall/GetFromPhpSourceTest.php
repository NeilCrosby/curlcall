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

    public function tearDown() {
        // on teardown delete the cache
        $dir = opendir(CACHE_PATH);
        while ($file = readdir($dir)) {
            if ( '.' == $file || '..' == $file ) {
                continue;
            }
            unlink(CACHE_PATH.$file);
        }

        //closing the directory
        closedir($dir);
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

}
?>