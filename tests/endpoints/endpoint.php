<?php

$dataType = isset($_REQUEST['type'])   ? $_REQUEST['type']   : 'array';
$output   = isset($_REQUEST['output']) ? $_REQUEST['output'] : 'php';
$delay    = isset($_REQUEST['delay'])  ? $_REQUEST['delay']  : null;

if ($delay && is_numeric($delay)) {
    usleep($delay);
}

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
    case 'xml':
        $output = null;
        if (!is_array($data)) {
            $output = $data;
        } else {
            foreach ($data as $key=>$value) {
                $output .= "<node key='$key'>$value</node>";
            }
        }

        echo <<<XML
<?xml version="1.0" encoding="utf-8"?>
<data>$output</data>
XML;
        break;
    default:
        echo serialize($data);
}

?>