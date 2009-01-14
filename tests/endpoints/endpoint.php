<?php

$dataType = isset($_REQUEST['type'])   ? $_REQUEST['type']   : 'array';
$output   = isset($_REQUEST['output']) ? $_REQUEST['output'] : 'php';

$array = array('some_data', 'some other data');
$string = 'some_data';

$data = null;
switch($dataType) {
    case 'true':
        $data = true;
        break;
    case 'false':
        $data = false;
        break;
    case 'null':
        $data = null;
        break;
    case 'string':
        $data = $string;
        break;
    case 'cookie':
        $data = $_COOKIE;
        break;
    case 'get':
        $data = $_GET;
        break;
    case 'post':
        $data = $_POST;
        break;
    default:
        $data = $array;
}


switch ($output) {
    case 'json':
        echo json_encode($data);
        break;
//    case 'xml':
//        break;
    default:
        echo serialize($data);
}

?>