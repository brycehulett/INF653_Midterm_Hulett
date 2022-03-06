<?php
  class Quote {
    // DB Stuff
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $authorId;
    public $categoryId;
    public $author;
    public $category;

    // Constructor with DB
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get all quotes 
    public function read() {
      // Create query
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
        From quotes
        JOIN authors ON authors.id = quotes.authorId
        JOIN categories ON categories.id = quotes.categoryId
        ORDER BY quotes.id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readAuthor() {
      // Create query
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
        From quotes
        JOIN authors ON authors.id = quotes.authorId
        JOIN categories ON categories.id = quotes.categoryId
        WHERE quotes.authorId = ?
        ORDER BY quotes.id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->authorId);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readCategory() {
      // Create query
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
        From quotes
        JOIN authors ON authors.id = quotes.authorId
        JOIN categories ON categories.id = quotes.categoryId
        WHERE quotes.categoryId = ?
        ORDER BY quotes.id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->categoryId);

      // Execute query
      $stmt->execute();

      return $stmt;
    }
    public function readBoth() {
      // Create query
      $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
        From quotes
        JOIN authors ON authors.id = quotes.authorId
        JOIN categories ON categories.id = quotes.categoryId
        WHERE quotes.categoryId = :categoryId and quotes.authorId = :authorId
        ORDER BY quotes.id';

      // Prepare statement
      $stmt = $this->conn->prepare($query);

      // Clean data
      $this->authorId = htmlspecialchars(strip_tags($this->authorId));
      $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

      // Bind data
      $stmt-> bindParam(':authorId', $this->authorId);
      $stmt-> bindParam(':categoryId', $this->categoryId);

      // Execute query
      $stmt->execute();

      return $stmt;
    }

    // Get Single quote
    public function read_single(){
      // Create query
           // Create query
           $query = 'SELECT quotes.id, quotes.quote, authors.author, categories.category
           From quotes
           JOIN authors ON authors.id = quotes.authorId
           JOIN categories ON categories.id = quotes.categoryId
           WHERE quotes.id = ?
           LIMIT 0, 1';

      //Prepare statement
      $stmt = $this->conn->prepare($query);

      // Bind ID
      $stmt->bindParam(1, $this->id);

      // Execute query
      $stmt->execute();

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      //var_dump($row);
      // set properties
      if($row && $row['quote']){
          $this->id = $row['id'];
          $this->quote = $row['quote'];
          $this->author = $row['author'];
          $this->category = $row['category'];
      }
  }

  // Create quote
  public function create() {

    // check existence of id's
    $query = 'SELECT * FROM authors WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $stmt-> bindParam(':id', $this->authorId);
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -2;
    }
    $query = 'SELECT * FROM categories WHERE id = :id LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId)); 
    $stmt-> bindParam(':id', $this->categoryId);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -3;
    }

    $query = 'INSERT INTO 
      quotes (quote, authorId, categoryId) 
    VALUES 
      (:quote, :authorId, :categoryId)';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->quote = htmlspecialchars(strip_tags($this->quote));
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

    // Bind data
    $stmt-> bindParam(':quote', $this->quote);
    $stmt-> bindParam(':authorId', $this->authorId);
    $stmt-> bindParam(':categoryId', $this->categoryId);

    // Execute query
    if($stmt->execute()) {
      return $this->conn->lastInsertId();
    }

    // Print error if something goes wrong
    printf("Error: $s.\n", $stmt->error);

    return -1;
  }

  // Update quote
  public function update() {

    $query = 'SELECT * FROM authors WHERE id = :aid LIMIT 1';
    $stmt = $this->conn->prepare($query);
    $this->authorId = htmlspecialchars(strip_tags($this->authorId));
    $stmt-> bindParam(':aid', $this->authorId);
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -2;
    }
    $query = 'SELECT * FROM categories WHERE id = :cid LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->categoryId = htmlspecialchars(strip_tags($this->categoryId)); 
    $stmt-> bindParam(':cid', $this->categoryId);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -3;
    }
    $query = 'SELECT * FROM quotes WHERE id = :qid LIMIT 1';
    $stmt = $this->conn->prepare($query);   
    $this->id = htmlspecialchars(strip_tags($this->id)); 
    $stmt-> bindParam(':qid', $this->id);   
    $stmt->execute();
    if($stmt->rowCount() == 0){
      return -4;
    }

    // Create Query
    $query = 'UPDATE quotes 
    SET
      quote = :quote,
      authorId = :authorId,
      categoryId = :categoryId 
    WHERE
      id = :id';

  // Prepare Statement
  $stmt = $this->conn->prepare($query);

  // Clean data
  $this->quote = htmlspecialchars(strip_tags($this->quote));
  $this->id = htmlspecialchars(strip_tags($this->id));

  // Bind data
  $stmt-> bindParam(':quote', $this->quote);
  $stmt-> bindParam(':id', $this->id);
  $stmt-> bindParam(':authorId', $this->authorId);
  $stmt-> bindParam(':categoryId', $this->categoryId);

  // Execute query
  //var_dump($stmt);
  if($stmt->execute()) {
    return $stmt->rowCount();
  }

  // Print error if something goes wrong
  printf("Error: $s.\n", $stmt->error);
  return false;
  }

  // Delete quote
  public function delete() {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt-> bindParam(':id', $this->id);

    // Execute query
    try {
      //code...
      if($stmt->execute()) {
        return $stmt->rowCount();
      }
    } catch (\Throwable $th) {
      //throw $th;
      // do nothing -> quote is in use
    }
    return -1;
    }
  }
