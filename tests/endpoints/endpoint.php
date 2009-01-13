<?php

$dataType = isset($_GET['type']) ? $_GET['type'] : 'array';
$output   = isset($_GET['output']) ? $_GET['output'] : 'php';

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