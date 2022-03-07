<?php
  // Headers
  // header('Access-Control-Allow-Origin: *');
  // header('Content-Type: application/json');
  // header('Access-Control-Allow-Methods: DELETE');
  // header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Quote object
  $quote = new Quote($db);
  
  // Get ID
  $quote->categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : -1;
  $quote->authorId = isset($_GET['authorId']) ? $_GET['authorId'] : -1;

  if ($quote->categoryId != -1 && $quote->authorId != -1 ) {
    $result = $quote->readBoth();
    if($result->rowCount() ==0){
      $trash = $quote->readAuthor();
      if($trash->rowCount() ==0){
        $array = array('message' => 'authorId Not Found');
      }else{
        $array = array('message' => 'categoryId Not Found');
      }
    }
  }else if ($quote->categoryId != -1) {
    $result = $quote->readCategory();
    if($result->rowCount() ==0){
      $array = array('message' => 'categoryId Not Found');
    }
  }else if ($quote->authorId != -1 ) {
    $result = $quote->readAuthor();
    if($result->rowCount() ==0){
      $array = array('message' => 'authorId Not Found');
    }
  }else{
    $result = $quote->read();
    if($result->rowCount() ==0){
      $array = array('message' => 'No Categories Found');
    }
  }

  // quote read query
  //$result = $quote->read();
  
  // Get row count
  $num = $result->rowCount();

  // Check if any categories
  if($num > 0) {
        // Cat array
        $cat_arr = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
          extract($row);
          array_push($cat_arr, (object)[
            'id' => $id, 
            'quote' => $quote,
            'author' => $author,
            'category' => $category
          ]);
        }

        // Turn to JSON & output
        echo json_encode($cat_arr);

  } else {
    // No matches
    echo json_encode($array);
  }
