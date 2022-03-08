<?php 
  
  class Database {

    private $hostname;
    private $database;
    private $username;
    private $password;

    private $conn;

    // DB Connect
    public function connect() {
      $url = getenv('JAWSDB_URL');
      $dbparts = parse_url($url);
  
      $hostname = $dbparts['host'];
      $username = $dbparts['user'];
      $password = $dbparts['pass'];
      $database = ltrim($dbparts['path'],'/');
      //You cannot do the above for your local dev environment, just Heroku
  
      // Create your new PDO connection here
      // This is also from the Heroku docs showing the PDO connection: 
      try {
        //$conn = new PDO("mysql:host=$hostname;dbname=$database;**charset=utf8**",$username,$password);
        $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "Connected successfully";
        return $conn;
      }
      catch(PDOException $e)
      {
        echo "Connection failed: " . $e->getMessage();
      }
      // We used this PDO connection format in previous weeks - reference w3schools.com
    }
  }
  
  // class Database {
  //   // DB Params
  //   private $host = 'localhost';
  //   private $db_name = 'quotesdb';
  //   private $username = 'root';
  //   private $password = '';
  //   private $conn;

  //   // DB Connect
  //   public function connect() {
  //     $this->conn = null;

  //     try { 
  //       $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
  //       $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //     } catch(PDOException $e) {
  //       echo 'Connection Error: ' . $e->getMessage();
  //     }

  //     return $this->conn;
  //   }
  // }