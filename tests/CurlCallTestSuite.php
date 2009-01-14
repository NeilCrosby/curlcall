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
        // TODO: PhpCache should actually do this if it can
        mkdir(CACHE_PATH, 0777, true);
    }
 
    protected function tearDown() {
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

    public static function validSourceCookieProvider($output='php') {
        $array = array(
            array(array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=cookie', 'key=valuepair')),
            array(array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=cookie', 'blah=bloo')),
            array(array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=cookie', 'blah=bloo;key=valuepair')),
        );
        
        foreach ($array as $key=>$value) {
            array_walk(
                $value, 
                array('CurlCallTestSuite', 'strReplaceWalker'), 
                array(
                    'find'=>'%output%', 
                    'replace'=>$output
                )
            );
            $array[$key] = $value;
        }
        return $array;
    }

    public static function validPostSourceProvider($output='php') {
        $array = array(
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%'), 'array'),
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=array'), 'array'),
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=string'), 'string'),
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=null'), 'null'),
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=true'), 'bool'),
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=false'), 'bool'),
        );
        
        foreach ($array as $key=>$value) {
            array_walk(
                $value, 
                array('CurlCallTestSuite', 'strReplaceWalker'), 
                array(
                    'find'=>'%output%', 
                    'replace'=>$output
                )
            );
            $array[$key] = $value;
        }
        return $array;
    }

    public static function validPostReturningPostSourceProvider($output='php') {
        $array = array(
            array(array(self::TEST_URL_STEM.'endpoint.php', 'output=%output%&type=post'), 'array'),
        );
        
        foreach ($array as $key=>$value) {
            array_walk(
                $value, 
                array('CurlCallTestSuite', 'strReplaceWalker'), 
                array(
                    'find'=>'%output%', 
                    'replace'=>$output
                )
            );
            $array[$key] = $value;
        }
        return $array;
    }

    public static function validGetReturningSourceProvider($output='php') {
        $array = array(
            array(self::TEST_URL_STEM.'endpoint.php?output=%output%&type=get', 'array'),
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
    
    public static function validPhpSourceCookieProvider() {
        return self::validSourceCookieProvider('php');
    }

    public static function validJsonSourceCookieProvider() {
        return self::validSourceCookieProvider('json');
    }

    public static function validPhpPostSourceProvider() {
        return self::validPostSourceProvider('php');
    }

    public static function validJsonPostSourceProvider() {
        return self::validPostSourceProvider('json');
    }

    public static function validPhpPostReturningPostSourceProvider() {
        return self::validPostReturningPostSourceProvider('php');
    }

    public static function validJsonPostReturningPostSourceProvider() {
        return self::validPostReturningPostSourceProvider('json');
    }

    public static function validPhpGetReturningSourceProvider() {
        return self::validGetReturningSourceProvider('php');
    }

    public static function validJsonGetReturningSourceProvider() {
        return self::validGetReturningSourceProvider('json');
    }

}

if (PHPUnit_MAIN_METHOD == 'CurlCallTestSuite::main') {
     CurlCallTestSuite::main();
}
?>