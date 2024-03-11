<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

//handling POST Request (create new orders)
if ($method === 'POST') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents('php://input'), true);

    //extracting data from decoded data
    $status = $data['status'];
    $quantities = $data['quantities'];
    $cart = $data['cart'];
    
    //inserting Orders data
    $sql = "INSERT INTO Orders (status, quantities, cart) VALUES ('$status', $quantities, $cart)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "New Orders created successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling GET Request (retrieve existing Orderss)
elseif ($method === 'GET') {
    // fetching Orderss
    $sql = "SELECT * FROM Orders";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $orders = array();
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode($orders);
    } else {
        echo json_encode(array());
    }
}

//handling PUT Request (updating data from existing Orders)
elseif ($method === 'PUT') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting data from decoded data
    $order_id = $data['order_id'];
    $status = $data['status'];
    $quantities = $data['quantities'];
    $cart = $data['cart'];
 
    $sql = "UPDATE Orders SET status='$status', quantities=$quantities WHERE order_id=$order_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Order data is updated successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling DELETE Request (deleting a Orders)
elseif ($method === 'DELETE') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting order_id from decoded data
    $order_id = $data['order_id'];

    //deleting a Orders
    $sql = "DELETE FROM Orders WHERE order_id=$order_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Orders data is deleted successfully"));
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
