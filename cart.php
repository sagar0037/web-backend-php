<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

//handling POST Request (create new cart)
if ($method === 'POST') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents('php://input'), true);

    //extracting data from decoded data
    $products = $data['products'];
    $quantities = $data['quantities'];
    $user = $data['user'];
    
    //inserting cart data
    $sql = "INSERT INTO Cart (products, quantities, user) VALUES ($products, $quantities, $user)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "New cart created successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling GET Request (retrieve existing carts)
elseif ($method === 'GET') {
    // fetching carts
    $sql = "SELECT * FROM Cart";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $carts = array();
        while($row = $result->fetch_assoc()) {
            $carts[] = $row;
        }
        echo json_encode($carts);
    } else {
        echo json_encode(array());
    }
}

//handling PUT Request (updating data from existing cart)
elseif ($method === 'PUT') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting data from decoded data
    $cart_id = $data['cart_id'];
    $products = $data['products'];
    $quantities = $data['quantities'];
    $user = $data['user'];

    $sql = "UPDATE Cart SET quantities=$quantities WHERE cart_id=$cart_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Cart is updated successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling DELETE Request (deleting a cart)
elseif ($method === 'DELETE') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting cart_id from decoded data
    $cart_id = $data['cart_id'];

    //deleting a cart
    $sql = "DELETE FROM Cart WHERE cart_id=$cart_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Cart data is deleted successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling other requests
else{
    http_response_code(405);
    echo json_encode(array("message" => "Unsupported HTTP method"));
    exit;

}

//closing database connection
$conn->close();

?>
