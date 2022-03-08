<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $category = new Category($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  //echo var_dump($data->category);
  if (!property_exists($data, 'category')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }


  $category->category = $data->category;

  // Create Category
  $id = $category->create();
  if($id != -1) {
    echo json_encode(
      array(
        'id' => $id,
        'author' => $category->category
      )
    );
  } else {
    echo json_encode(
      array('message' => 'Category Not Created')
    );
  }

