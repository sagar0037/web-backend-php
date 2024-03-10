<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

//handling POST Request (create new product)
if ($method === 'POST') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents('php://input'), true);

    //extracting data from decoded data
    $description = $data['description']; 
    $image = $data['image'];
    $pricing = $data['pricing'];
    $shipping_cost = $data['shipping_cost'];

    //inserting product data
    $sql = "INSERT INTO Product (description, image, pricing, shipping_cost) VALUES ('$description', '$image', $pricing, $shipping_cost)";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "New product created successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling GET Request (retrieve existing products)
elseif ($method === 'GET') {
    // fetching products
    $sql = "SELECT * FROM Product";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = array();
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        echo json_encode($products);
    } else {
        echo json_encode(array());
    }
}

//handling PUT Request (updating data from existing product)
elseif ($method === 'PUT') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting data from decoded data
    $product_id = $data['product_id'];
    $description = $data['description'];
    $image = $data['image'];
    $pricing = $data['pricing'];
    $shipping_cost = $data['shipping_cost'];

    $sql = "UPDATE Product SET description='$description', image='$image', pricing=$pricing, shipping_cost=$shipping_cost WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Product is updated successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling DELETE Request (deleting a product)
elseif ($method === 'DELETE') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting product_id from decoded data
    $product_id = $data['product_id'];

    //deleting a product
    $sql = "DELETE FROM Product WHERE product_id=$product_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "Product is deleted successfully"));
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
