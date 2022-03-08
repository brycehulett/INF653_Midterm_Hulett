<?php
  //Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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


  //var_dump($data);
  if (!property_exists($data, 'author') || !property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }
  // Set ID to UPDATE
  $author->id = $data->id;

  $author->author = $data->author;

  // Update post
  if($author->update()) {
    echo json_encode(
      array('id' => $author->id, 'author' => $author->author)
    );
  } else {
      $author->author = -1;
      $author->read_single(); // row is empty? for update on same id/author
      if($author->author != -1){
        echo json_encode(
          array('id' => $author->id, 'author' => $author->author)
        );
      }else{
      echo json_encode(
        array('message' => 'authorId Not Found')
      );
    }
  }
