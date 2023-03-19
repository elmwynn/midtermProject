<?php
class Quote {
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author_id;
    public $category_id;
    public $author;
    public $category;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read(){
        $query = 'SELECT quotes.id, quote, author, category FROM ' . $this->table . ' INNER JOIN authors ON quotes.author_id = authors.id INNER JOIN categories ON quotes.category_id = categories.id';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function read_single(){


        if(str_contains($_SERVER['QUERY_STRING'],'&')){
            $query = 'SELECT quotes.id, author, quote, category FROM ' . $this-> table . ' INNER JOIN authors ON quotes.author_id = authors.id INNER JOIN categories ON quotes.category_id = categories.id WHERE author_id = ? AND category_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->author_id);
            $stmt->bindParam(2, $this->category_id);
            $stmt->execute();
            return $stmt;
        }

        else if(str_contains($_SERVER['QUERY_STRING'],'author_id')){
            $query = 'SELECT quotes.id, author, quote, category FROM ' . $this-> table . ' INNER JOIN authors ON quotes.author_id = authors.id INNER JOIN categories ON quotes.category_id = categories.id WHERE author_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->author_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                if(is_null($row['id']))
                    return false;
                else {
                    
                    return $stmt;
                }
              }  
              else
                return false;    
        }

        else if(str_contains($_SERVER['QUERY_STRING'],'category_id')){
            $query = 'SELECT quotes.id, quote, author, category FROM ' . $this-> table . 
            ' INNER JOIN authors ON quotes.author_id = authors.id INNER JOIN categories ON quotes.category_id = categories.id WHERE category_id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->category_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                if(is_null($row['id']))
                    return false;
                else 
                    return $stmt;
              }  
              else
                return false;     
        }

        
        else if(str_contains($_SERVER['QUERY_STRING'],'id')){
            $query = 'SELECT quotes.id, author, quote, category FROM ' . $this-> table . ' INNER JOIN authors ON quotes.author_id = authors.id INNER JOIN categories ON quotes.category_id = categories.id WHERE quotes.id = ?';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                if(is_null($row['id']))
                    return false;
                else {
                    $this->quote = $row['quote'];
                    $this->author = $row['author'];
                    $this->category = $row['category'];  
                    return true;
                }
              }  
              else
                return false;    
        }
    }

    public function create(){

        $testQueryOne = 'SELECT * FROM ' . $this->table . ' WHERE author_id = :author_id';
        $stmtTest = $this->conn->prepare($testQueryOne); 
        $stmtTest->bindParam(':author_id', $this->author_id);
        if($stmtTest->execute()){
          $row = $stmtTest->fetch(PDO::FETCH_ASSOC);
          if(!($row)){
            echo json_encode(array('message' => 'author_id Not Found'));
           return false;
          }
        }
        $testQueryTwo = 'SELECT * FROM ' . $this->table . ' WHERE category_id = :category_id';
        $stmtTestTwo = $this->conn->prepare($testQueryTwo); 
        $stmtTestTwo->bindParam(':category_id', $this->category_id);
        if($stmtTestTwo->execute()){
          $row = $stmtTestTwo->fetch(PDO::FETCH_ASSOC);
          if(!($row)){
             echo json_encode(array('message' => 'category_id Not Found'));
             return false;
          }
        }

  
        $query = 'INSERT INTO ' . $this->table . '(quote, author_id, category_id) VALUES(:quote, :author_id, :category_id) RETURNING id';
        $stmt = $this->conn->prepare($query); 
        
        $this->quote =htmlspecialchars(strip_tags($this->quote));
        $this->author_id =htmlspecialchars(strip_tags($this->author_id));
        $this->category_id =htmlspecialchars(strip_tags($this->category_id));
        
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            return true;
        }
        return false;    
       }


    public function update(){

        $testQueryOne = 'SELECT * FROM ' . $this->table . ' WHERE author_id = :author_id';
        $stmtTest = $this->conn->prepare($testQueryOne); 
        $stmtTest->bindParam(':author_id', $this->author_id);
        if($stmtTest->execute()){
          $row = $stmtTest->fetch(PDO::FETCH_ASSOC);
          if(!($row)){
            echo json_encode(array('message' => 'author_id Not Found'));
           return false;
          }
        }
        $testQueryTwo = 'SELECT * FROM ' . $this->table . ' WHERE category_id = :category_id';
        $stmtTestTwo = $this->conn->prepare($testQueryTwo); 
        $stmtTestTwo->bindParam(':category_id', $this->category_id);
        if($stmtTestTwo->execute()){
          $row = $stmtTestTwo->fetch(PDO::FETCH_ASSOC);
          if(!($row)){
             echo json_encode(array('message' => 'category_id Not Found'));
             return false;
          }
        }

        $query = 'UPDATE ' . $this->table . ' SET quote = :quote, author_id = :author_id, category_id = :category_id
         WHERE id = :id RETURNING id';
        $stmt = $this->conn->prepare($query);

        $this->quote =htmlspecialchars(strip_tags($this->quote));
        $this->id =htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if($row){
                if(is_null($row['id'])){
                    echo json_encode(array('message'=> 'No Quotes Found'));
                    return false;
                }
                else
                  return true;
              }  
              else {
                echo json_encode(array('message'=> 'No Quotes Found'));
                return false;
              }
        }
      
    }

    public function delete(){
        
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id RETURNING id';

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          if($row){
            if(is_null($row['id']))
              return false;
            else
              return true;
          }  
          else
            return false;
        }
    
  }
}


?>