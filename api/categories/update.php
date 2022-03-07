<?php
  //Headers
  // header('Access-Control-Allow-Origin: *');
  // header('Content-Type: application/json');
  // header('Access-Control-Allow-Methods: PUT');
  // header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $category = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));


  //var_dump($data);
  if (!property_exists($data, 'category') || !property_exists($data, 'id')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }
  // Set ID to UPDATE
  $category->id = $data->id;

  $category->category = $data->category;

  // Update post
  if($category->update()) {
    echo json_encode(
      array('id' => $category->id, 'category' => $category->category)
    );
  } else {
      $category->read_single(); // row is empty? for update on same id/category
      if($category->category){
        echo json_encode(
          array('id' => $category->id, 'category' => $category->category)
        );
      }else{
      echo json_encode(
        array('message' => 'categoryId Not Found')
      );
    }
  }
