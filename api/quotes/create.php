<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quote = new Quote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  //echo var_dump($data->quote);
  if (!property_exists($data, 'quote') || !property_exists($data, 'authorId') || !property_exists($data, 'categoryId')) {
    echo json_encode(
      array('message' => 'Missing Required Parameters')
    );
    return;
  }


  $quote->quote = $data->quote;
  $quote->authorId = $data->authorId;
  $quote->categoryId = $data->categoryId;

  // Create quote
  $id = $quote->create();
  if($id > 0) {
    echo json_encode(
      array(
        'id' => $id,
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId
      )
    );
  } else if($id == -2){
    echo json_encode(
      array('message' => 'authorId Not Found')
    );
  }else if($id == -3){
    echo json_encode(
      array('message' => 'categoryId Not Found')
    );
  }else {
    echo json_encode(
      array('message' => 'Quote Not Created')
    );
  }

