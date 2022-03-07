<?php
  // Headers
  // header('Access-Control-Allow-Origin: *');
  // header('Content-Type: application/json');
  // header('Access-Control-Allow-Methods: POST');
  // header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $author = new Author($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  //echo var_dump($data->author);
  if (!property_exists($data, 'author')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }


  $author->author = $data->author;

  // Create author
  $id = $author->create();
  if($id != -1) {
    echo json_encode(
      array(
        'id' => $id,
        'author' => $author->author
      )
    );
  } else {
    echo json_encode(
      array('message' => 'Author Not Created')
    );
  }

