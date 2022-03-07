<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    //header('Content-Type: text');
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    }
echo
"<h1>Midterm Project Hulett</h1>";
?>