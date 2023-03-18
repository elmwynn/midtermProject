<?php
    

    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    $database = new Database();
    $db = $database->connect();

    $newAuthor = new Author($db);

    $data = json_decode(file_get_contents("php://input"));
    if(isset($data->author)){
        $newAuthor->author = $data->author;
       // $newAuthor->id = $data->id;
        if($newAuthor->create()){
            $author_arr = array(
                'id' => $newAuthor->id,
                'author' => $newAuthor->author
            );
            print_r(json_encode($author_arr));
        }
        else{
        echo json_encode (array('message' => 'Author Not Created'));
        }
    }
    else{
        echo json_encode (array('message' => 'Missing Required Parameter'));
    }