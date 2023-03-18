<?php
    
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');
    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    $database = new Database();
    $db = $database->connect();

    $newQuote = new Quote($db);

    $data = json_decode(file_get_contents("php://input"));

    if((isset($data->quote)) && (isset($data->author_id)) && (isset($data->category_id))){

        $newQuote->quote = $data->quote;
        $newQuote->author_id = $data->author_id;
        $newQuote->category_id = $data->category_id;
       // $newQuote->id = $data->id;

        if($newQuote->create()){
            $quote_arr = array(
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author' => $quote->author,
                'category' => $quote->category
            );
            print_r(json_encode($quote_arr));
        }
        else{
        echo json_encode (array('message' => 'Quote Not Created'));
        }
    }
    else {
        echo json_encode (array('message' => 'Missing Required Parameters'));
    }


