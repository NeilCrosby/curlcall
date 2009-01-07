<?php

if ( !function_exists('__autoload') ) {
    function __autoload($class) {
        $class = str_replace( '_', '/', $class );
        $aLocations = array('../externals/frontend-test-suite/suite', '.', '..');

        foreach( $aLocations as $location ) {
            $file = "$location/$class.php";
            if ( file_exists( $file ) ) {
                include_once( $file );
                return;
            }
        }

        // Check to see if we managed to declare the class
        if (!class_exists($class, false)) {
            trigger_error("Unable to load class: $class", E_USER_WARNING);
        }
    }
}

if ( !defined('CACHE_PATH') ) {
    define('CACHE_PATH', '/tmp/cache/curlcalltest/');
}

/**
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCallTestSuite extends TheCodeTrainBaseTestSuite {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }
 
    public static function suite() {
        $tests = self::getTests(__FILE__);

        $suite = new CurlCallTestSuite();
        foreach ( $tests as $test ) {
            $suite->addTestSuite($test);
        }

        return $suite;
    }
    
    protected function setUp() {
    }
 
    protected function tearDown() {
    }
    
    const TEST_URL_STEM = 'http://curlcalltest:8888/';
    
    public static function validSourceProvider($output='php') {
        $array = array(
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%', 'array'),
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=array', 'array'),
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=string', 'string'),
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=null', 'null'),
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=true', 'bool'),
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=false', 'bool'),
        );
        
        array_walk(
            $array, 
            array('CurlCallTestSuite', 'strReplaceWalker'), 
            array(
                'find'=>'%output%', 
                'replace'=>$output
            )
        );
        return $array;
    }

    private static function strReplaceWalker(&$item, $key, $aOptions) {
        $item = str_replace($aOptions['find'], $aOptions['replace'], $item);
    }

    public static function validPhpSourceProvider() {
        return self::validSourceProvider('php');
    }

    public static function validJsonSourceProvider() {
        return self::validSourceProvider('json');
    }

    public static function validXmlSourceProvider() {
        // TODO not sure how to endpoint the xml test data yet
        //return self::validSourceProvider('xml');
    }
    
}

if (PHPUnit_MAIN_METHOD == 'CurlCallTestSuite::main') {
     CurlCallTestSuite::main();
}
?>