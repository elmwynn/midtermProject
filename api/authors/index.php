<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];


if ($method === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

else if($method === 'GET'){
    if(str_contains($_SERVER['QUERY_STRING'],'id'))
        require 'read_single.php';
    else
        require 'read.php';
    exit();
}

else if ($method === 'POST') {
    require 'create.php';
    exit();
}

else if ($method === 'PUT'){
    require 'update.php';
    exit();
}

else if ($method === 'DELETE'){
    require 'delete.php';
    exit();
}


?>