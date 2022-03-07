<?php 
  
  require_once realpath(__DIR__ . '/vendor/autoload/.php');
  use Dotenv\Dotenv;
  $dotenv = Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  class Database {
    
    // DB Params
    private $host = 'kutnpvrhom7lki7u.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
    private $db_name = 'mm0a8iyg1d28s85z';
    private $username = 'ygi5gl3vy9z8gali';
    private $password;
    private $conn;

    // DB Connect
    public function connect() {
      $this->conn = null;
      $this->password = getenv("PASSWORD");

      try { 
        $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }
  }