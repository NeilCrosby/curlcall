<?php

require_once('externals/phpcache/PhpCache.php');

/**
 * A little library for making curl calls a little easier.
 *
 * Example Usage:
 *
 *     $curl = new CurlCall();
 *     $result = $curl->getFromJsonSource($url);
 *
 * $result is a PHP array.
 * 
 * @author  Neil Crosby <neil@neilcrosby.com>
 * @license Creative Commons Attribution-Share Alike 3.0 Unported 
 *          http://creativecommons.org/licenses/by-sa/3.0/
 **/
class CurlCall {
    
    public function __construct() {
        
    }
    
    public function getFromPhpSource($url, $aOptions=array()) {
        if (!is_array($url)) {
            $aOptions['type'] = 'php';
        } else {
            foreach ( $url as $key=>$value) {
                $aOptions[$key]['type'] = 'php';
            }
        }
        
        return $this->get($url, $aOptions);
    }

    public function getFromJsonSource($url, $aOptions=array()) {
        if (!is_array($url)) {
            $aOptions['type'] = 'json';
        } else {
            foreach ( $url as $key=>$value) {
                $aOptions[$key]['type'] = 'json';
            }
        }
        
        return $this->get($url, $aOptions);
    }

    public function getFromXmlSource($url, $aOptions=array()) {
        if (!is_array($url)) {
            $aOptions['type'] = 'xml';
        } else {
            foreach ( $url as $key=>$value) {
                $aOptions[$key]['type'] = 'xml';
            }
        }
        
        return $this->get($url, $aOptions);
    }

    public function getFromPhpSourceAsPost($url, $aOptions=array()) {
        $postFields = isset($aOptions['post-fields']) ? $aOptions['post-fields'] : '';

        if (!isset($aOptions['curlopts'])) {
            $aOptions['curlopts'] = array();
        }
        
        $aOptions['curlopts'][CURLOPT_POST] = 1;
        $aOptions['curlopts'][CURLOPT_POSTFIELDS] = $postFields;
        $aOptions['curlopts'][CURLOPT_HTTPHEADER] = array(
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-us,en;q=0.5",
            "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
            "Keep-Alive: 300",
            "Connection: keep-alive",
            "Pragma: no-cache",
            "Cache-Control: no-cache",
            "Expect: "
        );

        $aOptions['type'] = 'php';
        return $this->get($url, $aOptions);
    }

    private function get($url, $aOptions=array()) {
        if (is_array($url)) {
            return $this->getMulti($url, $aOptions);
        }
        
        $cacheTime  = isset($aOptions['cache-time'])  ? $aOptions['cache-time']  : 60 * 60 * 24 * 30; // 30 Days default
        $type       = isset($aOptions['type'])        ? $aOptions['type']        : null;
        $cacheIdent = isset($aOptions['cache-ident']) ? $aOptions['cache-ident'] : '';
        $userAgent  = isset($aOptions['user-agent'])  ? $aOptions['user-agent']  : 'CurlCall (http://github.com/NeilCrosby/curlcall/)';
        $curlOpts   = isset($aOptions['curlopts'])    ? $aOptions['curlopts']    : null;
        
        $cache = new PhpCache( $url.serialize($curlOpts), $cacheTime, $cacheIdent );

        if ( $cache->check() ) {

            $result = $cache->get();
            $datatype = $result['datatype'];
            $result = $result['data'];
            if ( 'xml' == $datatype ) {
                $result = simplexml_load_string($result);
            }
            
            return $result;

        }

        $session = curl_init();

        // set any headers the user wants
        if ( is_array($curlOpts) ) {
            foreach ($curlOpts as $key => $value) {
                curl_setopt($session, $key, $value); 
            }
        }

        // then set our expected headers
        curl_setopt( $session, CURLOPT_URL, $url );
        curl_setopt( $session, CURLOPT_HEADER, false );
        curl_setopt( $session, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $session, CURLOPT_USERAGENT, $userAgent );

        $result = curl_exec( $session );
        $cacheResult = $result;
        curl_close( $session );

        switch ($type) {
            case 'json':
                $result = json_decode($result, true);
                $cacheResult = $result;
                $datatype = 'php'; // ya rly
                break;
            case 'xml':
                $result = simplexml_load_string($result);
                $datatype = 'xml';
                break;
            default:
                $result = unserialize($result);
                $cacheResult = $result;
                $datatype = 'php';
        }
        
        $cache->set(
            array(
                'url'=>$url,
                'method'=>'get',
                'datatype'=>$datatype,
                'data'=>$cacheResult
            )
        );
        
        return $result;
    }
    
    private function getMulti($urls, $aOptions=array()) {
        $results = array();
        $mh = curl_multi_init();

        $handles = array();
        
        foreach ( $urls as $key=>$url ) {

            $cacheTime  = isset($aOptions[$key]) && isset($aOptions[$key]['cache-time'])  ? $aOptions[$key]['cache-time']  : 60 * 60 * 24 * 30; // 30 Days default
            $type       = isset($aOptions[$key]) && isset($aOptions[$key]['type'])        ? $aOptions[$key]['type']        : null;
            $cacheIdent = isset($aOptions[$key]) && isset($aOptions[$key]['cache-ident']) ? $aOptions[$key]['cache-ident'] : '';
            $curlOpts   = isset($aOptions[$key]) && isset($aOptions[$key]['curlopts'])    ? $aOptions[$key]['curlopts']    : null;

            $cache = new PhpCache( $url.serialize($curlOpts), $cacheTime, $cacheIdent );

            if ( $cache->check() && 1==0) {

                $result = $cache->get();
                $datatype = $result['datatype'];
                $result = $result['data'];
                if ( 'xml' == $datatype ) {
                    $result = simplexml_load_string($result);
                }

                $results[$key] = $result;

            } else {

                $handles[$key] = curl_init();

                // set any headers the user wants
                if ( is_array($curlOpts) ) {
                    foreach ($curlOpts as $key => $value) {
                        curl_setopt($session, $key, $value); 
                    }
                }

                // then set our expected headers
                curl_setopt( $handles[$key], CURLOPT_URL, $url );
                curl_setopt( $handles[$key], CURLOPT_HEADER, false );
                curl_setopt( $handles[$key], CURLOPT_RETURNTRANSFER, 1 );
            
                curl_multi_add_handle($mh,$handles[$key]);

            }

        }


        $active = null;
        //execute the handles
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh, $active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }

        //close the handles
        foreach ($handles as $key=>$handle) {
            $result = curl_multi_getcontent($handle);
            
            $cacheResult = $result;

            $cacheTime  = isset($aOptions[$key]) && isset($aOptions[$key]['cache-time'])  ? $aOptions[$key]['cache-time']  : 60 * 60 * 24 * 30; // 30 Days default
            $type       = isset($aOptions[$key]) && isset($aOptions[$key]['type'])        ? $aOptions[$key]['type']        : null;
            $cacheIdent = isset($aOptions[$key]) && isset($aOptions[$key]['cache-ident']) ? $aOptions[$key]['cache-ident'] : '';
            $curlOpts   = isset($aOptions[$key]) && isset($aOptions[$key]['curlopts'])    ? $aOptions[$key]['curlopts']    : null;

            $cache = new PhpCache( $url.serialize($curlOpts), $cacheTime, $cacheIdent );

            switch ($type) {
                case 'json':
                    $result = json_decode($result, true);
                    $cacheResult = $result;
                    $datatype = 'php'; // ya rly
                    break;
                case 'xml':
                    $result = simplexml_load_string($result);
                    $datatype = 'xml';
                    break;
                default:
                    $result = unserialize($result);
                    $cacheResult = $result;
                    $datatype = 'php';
            }

            $cache->set(
                array(
                    'url'=>$url,
                    'method'=>'get',
                    'datatype'=>$datatype,
                    'data'=>$cacheResult
                )
            );

            $results[$key] = $result;

            curl_multi_remove_handle($mh, $handle);
        }
        curl_multi_close($mh);

        return $results;
    }
}

