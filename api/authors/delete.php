<?php
  //Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $author = new Author($db);
  
  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if (!property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }

  // Set ID to UPDATE
  $author->id = $data->id;

  // Delete post
  $response = $author->delete();
  if($response > 0) {
    echo json_encode(
      array('id' => $author->id)
    );
  } else if($response == -1){
    echo json_encode(
      array('message' => 'Cant delete: foreign key in use')
    );
  }else {
    echo json_encode(
      array('message' => 'authorId Not Found')
    );
  }
