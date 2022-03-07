<?php
    cors();
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    //header('Content-Type: text');
    //$method = $_SERVER['REQUEST_METHOD'];
   // if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    //}

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';
  
   
    $database = new Database();
    $db = $database->connect();
  
   
    $author = new Author($db);

    //var_dump($method);
    
    $method = $_SERVER['REQUEST_METHOD'];
    //$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));

    switch ($method) {
    case 'PUT':
        require 'update.php';  
        break;
    case 'POST':
        require 'create.php'; 
        break;
    case 'DELETE':
        require 'delete.php'; 
        break;
    case 'GET':
        // Get ID
        if(isset($_GET['id'])) {
            require 'read_single.php';
        }
        else{
            require 'read.php';
        }
        break;
    default:
        //handle_error($request);  
        echo 'what the?';
        break;
    }

function cors() {
    
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }
    
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        
        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
        exit(0);
    }
    
    echo "You have CORS!";
}