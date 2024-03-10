<?php

//including the database connection
require_once('conn.php');

//specifying content type
header("Content-Type: application/json; charset=UTF-8");

$method = $_SERVER['REQUEST_METHOD'];

//handling POST Request (create new user)
if ($method === 'POST') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents('php://input'), true);

    //extracting data from decoded data
    $email = $data['email']; 
    $password = $data['password'];
    $username = $data['username'];
    $purchase_history = $data['purchase_history'];
    $shipping_address = $data['shipping_address'];

    //inserting user data
    $sql = "INSERT INTO Users (email, password, username, purchase_history, shipping_address) VALUES ('$email', '$password', '$username', '$purchase_history', '$shipping_address')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "New user created successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling GET Request (retrieve existing users)
elseif ($method === 'GET') {
    // fetching users
    $sql = "SELECT * FROM Users";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $users = array();
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    } else {
        echo json_encode(array());
    }
}

//handling PUT Request (updating data from existing user)
elseif ($method === 'PUT') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting data from decoded data
    $user_id = $data['user_id'];
    $email = $data['email']; 
    $password = $data['password'];
    $username = $data['username'];
    $purchase_history = $data['purchase_history'];
    $shipping_address = $data['shipping_address'];

    $sql = "UPDATE Users SET email='$email', password='$password', username='$username', purchase_history='$purchase_history', shipping_address='$shipping_address' WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "User is updated successfully"));
    } else {
        echo json_encode(array("message" => "Error: " . $conn->error));
    }
}

//handling DELETE Request (deleting a user)
elseif ($method === 'DELETE') {
    //decoding JSON data from request data
    $data = json_decode(file_get_contents("php://input"), true);

    //extracting user_id from decoded data
    $user_id = $data['user_id'];

    //deleting a user
    $sql = "DELETE FROM Users WHERE user_id=$user_id";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(array("message" => "User data is deleted successfully"));
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
